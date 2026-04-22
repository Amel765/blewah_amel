# 📋 Admin Auto-Process Flow Documentation

## 🎯 Overview

**Auto-Process** adalah fitur yang memungkinkan admin untuk secara otomatis memproses pengajuan (submission) user dengan metode **AHP** (Analytic Hierarchy Process) untuk pembobotan kriteria dan **CoCoSo** (Combined Compromise Solution) untuk perankingan alternatif.

**Route:** `POST /admin/submissions/{id}/auto-process`  
**Controller:** `AdminSubmissionController::autoProcess($id)`  
**Status Transition:** `pending` → `processed`

---

## 📊 Data Flow Diagram

```
┌─────────────────────────────────────────────────────────────────────┐
│                    ADMIN AUTO-PROCESS WORKFLOW                      │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  ┌────────────────────┐                                           │
│  │  User Submission   │ (status = 'pending')                      │
│  │  - Criteria        │                                           │
│  │  - Alternatives    │                                           │
│  │  - Comparisons     │                                           │
│  │  - Scores          │                                           │
│  └──────────┬─────────┘                                           │
│             │                                                       │
│             ▼                                                       │
│  ┌─────────────────────────────────────────────────────┐          │
│  │  Step 1: Load Submission with Relations           │          │
│  │  - criteria()                                       │          │
│  │  - alternatives()                                    │          │
│  └────────────────────────────┬────────────────────────┘          │
│                               ▼                                      │
│  ┌─────────────────────────────────────────────────────┐          │
│  │  Step 2: AHP Calculation (AHPService)              │          │
│  │  ┌─────────────────────────────────────────────┐    │          │
│  │  │ Input: Submission ID                         │    │          │
│  │  │ - Get all criteria for this submission       │    │          │
│  │  │ - Build pairwise comparison matrix           │    │          │
│  │  │     from submission_comparisons table        │    │          │
│  │  │                                               │    │          │
│  │  │ Process:                                      │    │          │
│  │  │ 1. Normalize matrix (column sums)            │    │          │
│  │  │ 2. Calculate eigenvector (row averages)      │    │          │
│  │  │ 3. Normalize to weights (sum = 1)            │    │          │
│  │  │ 4. Calculate Consistency Ratio (CR)          │    │          │
│  │  └─────────────────────────────────────────────┘    │          │
│  │                                                     │          │
│  │  Output:                                           │          │
│  │  - weightsIndexed: [w1, w2, w3, ...]              │          │
│  │  - weights: [{criteria_id, name, weight}, ...]     │          │
│  │  - cr: float (Consistency Ratio)                   │          │
│  │  - matrix: original pairwise matrix                │          │
│  │  - criteria: collection of criteria models         │          │
│  └────────────────────────────┬────────────────────────┘          │
│                               ▼                                      │
│  ┌─────────────────────────────────────────────────────┐          │
│  │  Step 3: CR Validation                             │          │
│  │  if (CR >= 0.1) → abort with error                 │          │
│  │  (Matrix must be consistent per Saaty标准)          │          │
│  └────────────────────────────┬────────────────────────┴──┐       │
│                               ▼                             │       │
│  ┌─────────────────────────────────────────────────────┐    │       │
│  │  Step 4: CoCoSo Calculation (COCOSOService)        │    │       │
│  │  ┌─────────────────────────────────────────────┐    │    │       │
│  │  │ Input: weights + submission scores         │    │    │       │
│  │  │                                               │    │    │       │
│  │  │ Process:                                     │    │    │       │
│  │  │ 1. Normalize scores (0-100 → utility)      │    │    │       │
│  │  │ 2. Calculate Si = Σ (normalized × weight)  │    │    │       │
│  │  │ 3. Calculate Pi = Π (normalized^weight)    │    │    │       │
│  │  │ 4. Compute Ka, Kb, Kc                       │    │    │       │
│  │  │ 5. Qi = (Ka + Kb + Kc) / 3                 │    │    │       │
│  │  └─────────────────────────────────────────────┘    │    │       │
│  │                                                     │    │       │
│  │  Output: ranking[] array with:                     │    │       │
│  │  - name (alternative name)                         │    │       │
│  │  - rank (1, 2, 3...)                               │    │       │
│  │  - qi (final score)                                │    │       │
│  │  - si, pi, ka, kb, kc (intermediate values)       │    │       │
│  └────────────────────────────┬────────────────────────┴─────┘   │
│                               ▼                                     │
│  ┌─────────────────────────────────────────────────────┐          │
│  │  Step 5: Build result_data array                   │          │
│  │  {                                                   │          │
│  │    "manual": false,                                 │          │
│  │    "manual_text": "Auto-generated result...",      │          │
│  │    "ranking": [...],                                │          │
│  │    "weights": [{name, weight}, ...],                │          │
│  │    "cr": float,                                     │          │
│  │    "ci": float,   // derived from CR × RI           │          │
│  │    "ri": float,   // RI value for n criteria        │          │
│  │    "n_criteria": int,                               │          │
│  │    "calculated_at": timestamp                       │          │
│  │  }                                                   │          │
│  └────────────────────────────┬────────────────────────┘          │
│                               ▼                                      │
│  ┌─────────────────────────────────────────────────────┐          │
│  │  Step 6: Update Submission                          │         │
│  │  - status = 'processed'                             │         │
│  │  - result_data = JSON of result_data array          │         │
│  └────────────────────────────┬────────────────────────┘          │
│                               ▼                                      │
│  ┌─────────────────────────────────────────────────────┐          │
│  │  Step 7: User Notification                           │         │
│  │  - User can view results in submission_show page    │         │
│  │  - Status badge shows "Selesai Diproses"           │         │
│  │  - "Kirim Ulang" button appears (resend to draft)  │         │
│  └─────────────────────────────────────────────────────┘          │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 🔍 Detailed Step-by-Step Process

### **Step 1: Load Submission**
**File:** `AdminSubmissionController::autoProcess($id)`

```php
$submission = Submission::with('criteria', 'alternatives')->findOrFail($id);
```
- Loads submission along with its criteria and alternatives relationships.
- Throws 404 if submission not found.

### **Step 2: AHP Weight Calculation**
**Service:** `App\Services\AHPService::calculateWeights($submissionId)`

**Internal Process:**
1. **Fetch criteria:** `Criteria::where('submission_id', $submissionId)->orderBy('id')->get()`
2. **Build pairwise matrix** from `submission_comparisons` table:
   - For each pair (i, j), get value directly stored.
   - If not found, check reverse pair and compute reciprocal: `1 / reverse_value`.
   - Diagonal entries = 1.0.
3. **Normalize matrix:**
   - Sum each column.
   - Divide each element by its column sum.
4. **Calculate eigenvector (weights):**
   - Multiply across rows: `product = ∏ matrix[i][j]`
   - Take nth root: `weight_i = product^(1/n)`
   - Normalize: `weight_i = weight_i / sum(all weights)`
5. **Calculate Consistency Ratio (CR):**
   - Compute `λ_max` via `(A·w)_i / w_i` averaging.
   - `CI = (λ_max - n) / (n - 1)`
   - **For n ≤ 2:** CR = 0 (no consistency check needed).
   - **For n ≥ 3:** `CR = CI / RI[n]` where RI is Random Index.
   - If `CR >= 0.1` → inconsistent, abort.

**Returned structure:**
```json
{
  "weightsIndexed": [0.2395, 0.6232, 0.1373],
  "weights": {
    "criteria_id_1": {"criteria_id": 1, "name": "Harga", "weight": 0.2395},
    ...
  },
  "cr": 0.0153,
  "matrix": [...],
  "normalizedMatrix": [...],
  "criteria": [Collection of Criteria models]
}
```

### **Step 3: CR Validation**
```php
if (!isset($ahpResults['cr']) || $ahpResults['cr'] >= 0.1) {
    return back()->with('error', 'Consistency Ratio (CR) > 0.1. Silakan perbaiki perbandingan kriteria.');
}
```
- Rejects inconsistent pairwise comparisons.
- Admin must adjust comparison values until CR < 0.1.

### **Step 4: CoCoSo Ranking**
**Service:** `App\Services\COCOSOService::calculateRanking($weights, $globalWeights = null, $submissionId = null)`

**Input:**
- `$weights` from AHP (associative array by criteria_id).
- `$submissionId` to fetch `submission_scores`.

**Process:**
1. **Normalize scores** (Benefit: `x / max(x)`, Cost: `min(x) / x`).
2. **Calculate for each alternative i:**
   - **S_i (Weighted Sum):** `Σ (normalized_score_ij × weight_j)`
   - **P_i (Weighted Product):** `Π (normalized_score_ij ^ weight_j)` — computed in log space: `exp(Σ weight_j × ln(normalized_score_ij))`
3. **Compute three compromise scores:**
   - `K_a = (S_i - min_S) / (max_S - min_S) + (P_i - min_P) / (max_P - min_P)`
   - `K_b = S_i / min_S`  (relative to best S)
   - `K_c = (S_i / ΣS) + (P_i / ΣP)`
4. **Aggregate final score:**
   - `Q_i = (K_a × K_b × K_c)^(1/3) + (K_a + K_b + K_c) / 3`
5. **Rank** alternatives by `Q_i` descending.

**Returned:** Array of suggestions with alternative model and all intermediate scores.

### **Step 5: Derive CI from CR**
**Important:** The code derives `CI` from already-computed `CR` rather than recalculating:

```php
$cr = $ahpResults['cr'] ?? 0;
$ci = $cr * $riValue;  // Because CR = CI / RI
```

**Why?** `AHPService` already computed CR correctly. Re-deriving CI avoids duplicate math and potential rounding differences.

### **Step 6: Build result_data JSON**
Stored in `submissions.result_data` (JSON/array cast):

| Key | Type | Description |
|-----|------|-------------|
| `manual` | bool | `false` = auto-calculated |
| `manual_text` | string | Auto-generated summary sentence |
| `ranking` | array | List of ranked alternatives with Qi, Si, Pi, Ka, Kb, Kc |
| `weights` | array | List of criteria weights [{name, weight}] |
| `cr` | float | Consistency Ratio (0.0153 for example) |
| `ci` | float | Consistency Index (derived) |
| `ri` | float | Random Index value used (0.58 for n=3, 0.00 for n=2) |
| `n_criteria` | int | Number of criteria |
| `calculated_at` | string | Timestamp of auto-calculation |

### **Step 7: Update Submission**
```php
$submission->update([
    'status' => 'processed',
    'result_data' => $resultData,
]);
```
- Triggers model events (if any).
- `result_data` cast to `array` automatically (see `Submission.php`).

### **Step 8: User Notification**
- User sees status badge: **"Selesai Diproses"** (green).
- "Kirim Ulang" button appears → sends submission back to `draft` for editing.
- Results displayed in `submission_show.blade.php` with full AHP weights table and CoCoSo ranking.

---

## ⚠️ Consistency Ratio (CR) Validation Logic

### RI Table (Saaty Standard)

| n (criteria) | RI      |
|--------------|---------|
| 1            | 0.00    |
| 2            | 0.00    |
| 3            | 0.58    |
| 4            | 0.90    |
| 5            | 1.12    |
| 6            | 1.24    |
| 7            | 1.32    |
| 8            | 1.41    |
| 9            | 1.45    |
| 10           | 1.49    |

**Note:** For n ≤ 2, CI and CR are always 0 because pairwise comparison with only 2 criteria is always perfectly consistent.

**CR Threshold:** `CR < 0.1` → consistent (acceptable).  
**CR ≥ 0.1** → inconsistent → admin must revise comparison values.

---

## 🔢 Mathematical Formulas

### AHP

1. **Normalized Matrix:**  
   `n_ij = a_ij / Σ_i a_ij` (column-wise normalization)

2. **Weight (Eigenvector):**  
   `w_i = (∏_j n_ij)^(1/n) / Σ_k (∏_j n_kj)^(1/n)`

3. **λ_max (Principal Eigenvalue):**  
   `λ_max = (1/n) Σ_i [(A·w)_i / w_i]`

4. **Consistency Index (CI):**  
   `CI = (λ_max - n) / (n - 1)`

5. **Consistency Ratio (CR):**  
   `CR = CI / RI[n]`

### CoCoSo

1. **Normalized Performance:**  
   Benefit: `r_ij = x_ij / max(x_j)`  
   Cost: `r_ij = min(x_j) / x_ij`

2. **Weighted Sum:**  
   `S_i = Σ_j (r_ij × w_j)`

3. **Weighted Product:**  
   `P_i = Π_j (r_ij ^ w_j)`  
   (Use logs: `ln(P_i) = Σ_j w_j × ln(r_ij)`)

4. **Compromise Scores:**
   - `K_a = (S_i - min_S) / (max_S - min_S) + (P_i - min_P) / (max_P - min_P)`
   - `K_b = S_i / min_S`
   - `K_c = (S_i / ΣS) + (P_i / ΣP)`

5. **Final Score:**  
   `Q_i = (K_a × K_b × K_c)^(1/3) + (K_a + K_b + K_c) / 3`

6. **Ranking:** Sort by `Q_i` descending.

---

## 🛡️ Error Handling

| Condition | Handling |
|-----------|----------|
| CR ≥ 0.1 | Redirect back with error message; no result saved |
| Empty criteria (< 2) | Validation in user form prevents submission |
| Empty alternatives (< 2) | Validation in user form prevents submission |
| No comparisons stored | AHPService builds matrix with defaults (1.0); CR may be 0 |
| Exception during CoCoSo | Caught; returns empty suggestions; autoProcess fails gracefully |

---

## 📁 Files Involved

| File | Role |
|------|------|
| `AdminSubmissionController.php` | Orchestrates autoProcess flow |
| `AHPService.php` | Calculates weights and CR |
| `COCOSOService.php` | Calculates ranking scores |
| `Submission.php` (Model) | Has `result_data` cast to array |
| `submission_comparisons` table | Stores pairwise comparisons |
| `submission_scores` table | Stores alternative scores per criteria |
| `resources/views/pages/admin/submissions/input_result.blade.php` | Admin UI for manual override |
| `resources/views/pages/user/submission_show.blade.php` | User view of results |

---

## 🔄 User Journey After Auto-Process

1. Admin clicks **"Auto Process"** button on submission detail page.
2. System runs AHP → validates CR → runs CoCoSo.
3. If CR ≥ 0.1: error shown, admin adjusts comparisons.
4. If successful: submission status → `processed`, results saved.
5. User receives notification (implicit via status change).
6. User visits **"Detail Pengajuan"** page:
   - Sees **"Selesai Diproses"** badge.
   - Views AHP weight table and CoCoSo ranking table.
   - Can click **"Kirim Ulang"** to reset to draft and edit.

---

## 📈 Result Data Structure Example

```json
{
  "manual": false,
  "manual_text": "Hasil perhitungan otomatis dengan menggunakan metode AHP...",
  "ranking": [
    {
      "name": "Supplier C",
      "rank": 1,
      "qi": 78.97,
      "si": 79.01,
      "pi": 78.93,
      "ka": 78.97,
      "kb": 78.97,
      "kc": 78.97
    },
    ...
  ],
  "weights": [
    {"name": "Harga", "weight": 0.2395},
    {"name": "Kualitas", "weight": 0.6232},
    {"name": "Layanan", "weight": 0.1373}
  ],
  "cr": 0.0153,
  "ci": 0.0089,
  "ri": 0.58,
  "n_criteria": 3,
  "calculated_at": "2026-04-22 19:30:00"
}
```

---

## 🔧 Key Implementation Notes

1. **RI Indexing Fix:**  
   Previous code used 0-indexed array `[0, 0, 0.58, ...]` causing n=2 to read RI=0.58 (wrong). Fixed to associative: `[1=>0, 2=>0, 3=>0.58, ...]`.

2. **CR Early Return in AHPService:**  
   For n ≤ 2, CR is hardcoded to 0 since consistency is mathematically guaranteed.

3. **CI Derivation:**  
   CI is computed as `CR × RI` to avoid redundant eigenvector calculations. This is mathematically sound since CR was already computed from the actual λ_max.

4. **Decimal Step Removal:**  
   Input fields `step="0.1"` and `step="0.01"` changed to `step="any"` to allow unlimited decimal precision in user comparisons and scores.

5. **Resend Feature:**  
   Allows users to reset processed submissions back to `draft` status, clearing `result_data`, and returning to the data management page for edits.

---

## 🧪 Testing Checklist

- [x] **n=2 criteria:** CR = 0, RI = 0.00 (no inconsistency flag)
- [x] **n=3 criteria:** RI = 0.58, CR < 0.1 for consistent matrix
- [x] **CR ≥ 0.1:** Validation prevents auto-processing
- [x] **Result storage:** JSON saved correctly in `result_data` column
- [x] **User view:** Weights and ranking display correctly
- [x] **Resend flow:** Reset to draft, edit, re-submit works

---

## 📚 References

- Saaty, T.L. (1980). *The Analytic Hierarchy Process*. McGraw-Hill.
- CoCoSo Method: *Combined Compromise Solution* (Yazdani et al., 2019)
- Laravel Eloquent: Casting JSON attributes
- PHP: Number formatting with `rtrim` to remove trailing zeros

---

**Last Updated:** 2026-04-22  
**Version:** 1.1 (RI fix + resend feature)
