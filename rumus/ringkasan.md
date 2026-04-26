# Ringkasan Perubahan pada Sistem Perankingan COCOSO

## Latar Belakang
Sistem perankingan menggunakan metode COCOSO (Combined Compromise Solution) untuk menghitung nilai akhir (Qi) dari setiap alternatif berdasarkan nilai Si (Weighted Sum) dan Pi (Weighted Product).

## Perubahan Utama yang Dilakukan

### 1. Perubahan Rumus Ka, Kb, dan Kc
Sebelumnya, sistem menggunakan rumus berikut:
- Ka = (Si + Pi) / (sumSi + sumPi)
- Kb = (Si + Pi) / (minSi + minPi)
- Kc = (0.5*Si + 0.5*Pi) / (0.5*maxSi + 0.5*maxPi)

Setelah perubahan, sistem menggunakan rumus yang sesuai dengan Excel:
- Ka = ((Si + Pi) / (maxSi + maxPi)) + 0.5
- Kb = (Si/minSi) + (Pi/minPi)  [dengan pengecualian untuk pembagian dengan nol]
- Kc = ((0.5*Si + 0.5*Pi) / (0.5*maxSi + 0.5*maxPi))

### 2. Perubahan Rumus Qi
Rumus Qi yang digunakan adalah sesuai dengan rumus Excel yang diberikan:
- Qi = (PRODUCT(Ka,Kb,Kc)^(1/3)) + ((1/3) * SUM(Ka,Kb,Kc))
- Dalam kode: $qi_raw = pow($product, 1/3) + ((1/3) * $sumK);
- Dimana: $product = $ka * $kb * $kc; dan $sumK = $ka + $kb + $kc;

### 3. Pembulatan
Pembulatan hanya dilakukan pada nilai akhir Qi (2 angka di belakang koma), bukan pada nilai intermediate seperti Ka, Kb, dan Kc.

## Alur Perhitungan Lengkap

1. **Normalisasi Matrix**
   - Untuk tiap kriteria benefit: rij = x_ij / max(x_j)
   - Untuk tiap kriteria cost: rij = min(x_j) / x_ij

2. **Perhitungan Si dan Pi**
   - Si = Σ (w_j * r_ij) untuk setiap alternatif i
   - Pi = Π (r_ij ^ w_j) untuk setiap alternatif i

3. **Perhitungan Nilai Ekstrem**
   - maxSi = max(Si), minSi = min(Si)
   - maxPi = max(Pi), minPi = min(Pi)

4. **Perhitungan Ka, Kb, Kc untuk setiap alternatif**
   - Ka = ((Si + Pi) / (maxSi + maxPi)) + 0.5
   - Kb = (Si/minSi) + (Pi/minPi) [jika minSi dan minPi tidak nol]
   - Kc = ((0.5*Si + 0.5*Pi) / (0.5*maxSi + 0.5*maxPi))

5. **Perhitungan Qi untuk setiap alternatif**
   - product = Ka * Kb * Kc
   - sumK = Ka + Kb + Kc
   - Qi_raw = (product)^(1/3) + (1/3) * sumK
   - Qi = round(Qi_raw, 2)

6. **Perankingan**
   - Alternatif diurutkan berdasarkan nilai Qi dari tertinggi ke terendah

## Hasil yang Diharapkan
Setelah perubahan ini, sistem seharusnya menghasilkan nilai Qi yang sesuai dengan data Excel:
- Alternatif A1: Qi = 3.16
- Alternatif A3: Qi = 3.82
- Dengan perankingan: A3 (peringkat 1), A1 (peringkat 2)

## File yang Diubah
- app/Services/COCOSOService.php (fungsi calculateRanking)

## Catatan Penting
- Pastikan nilai minSi dan minPi tidak sama dengan nol sebelum melakukan pembagian dalam perhitungan Kb
- Pembulatan nilai intermediate (Ka, Kb, Kc) dalam array hasil hanya untuk tampilan, tidak memengaruhi perhitungan Qi akhir
- Rumus normalisasi matrix tetap sama seperti sebelumnya