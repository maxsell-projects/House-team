<?php

namespace App\Services;

class CapitalGainsCalculatorService
{
    // Coeficientes de desvalorização da moeda (Portaria do Governo - Tabela Oficial)
    // Valores numéricos convertidos (ex: 5 585,78 -> 5585.78)
    private const COEFFICIENTS = [
        // 2024 e 2025 (assumindo 1.00 para o corrente)
        2025 => 1.00, 2024 => 1.00, 
        2023 => 1.02, 
        2022 => 1.06, 
        2021 => 1.16, 
        // 2018 a 2020
        2020 => 1.17, 2019 => 1.17, 2018 => 1.17, 
        2017 => 1.18, 
        2016 => 1.19, 
        // 2012 a 2015
        2015 => 1.20, 2014 => 1.20, 2013 => 1.20, 2012 => 1.20, 
        2011 => 1.24, 
        2010 => 1.28, 
        2009 => 1.30, 
        2008 => 1.28, 
        2007 => 1.32, 
        2006 => 1.34, 
        2005 => 1.40, 
        2004 => 1.43, 
        2003 => 1.45, 
        2002 => 1.49, 
        2001 => 1.55, 
        2000 => 1.67, 
        1999 => 1.70, 
        1998 => 1.72, 
        1997 => 1.77, 
        1996 => 1.80, 
        1995 => 1.84, 
        1994 => 1.92, 
        1993 => 2.01, 
        1992 => 2.18, 
        1991 => 2.38, 
        1990 => 2.69, 
        1989 => 3.00, 
        1988 => 3.33, 
        1987 => 3.71, 
        1986 => 4.04, 
        1985 => 4.48, 
        1984 => 5.35, 
        1983 => 6.89, 
        1982 => 8.61, 
        1981 => 10.37, 
        1980 => 12.68, 
        1979 => 14.07, 
        1978 => 17.83, 
        1977 => 22.76, 
        1976 => 29.71, 
        1975 => 35.46, 
        1974 => 41.50, 
        1973 => 54.11, 
        1972 => 59.52, 
        1971 => 63.67, 
        1970 => 66.89, 
        // 1967 a 1969
        1969 => 72.24, 1968 => 72.24, 1967 => 72.24, 
        1966 => 77.26, 
        1965 => 80.83, 
        1964 => 83.92, 
        // 1958 a 1963
        1963 => 87.82, 1962 => 87.82, 1961 => 87.82, 1960 => 87.82, 1959 => 87.82, 1958 => 87.82, 
        // 1951 a 1957
        1957 => 93.40, 1956 => 93.40, 1955 => 93.40, 1954 => 93.40, 1953 => 93.40, 1952 => 93.40, 1951 => 93.40, 
        // 1944 a 1950
        1950 => 101.78, 1949 => 101.78, 1948 => 101.78, 1947 => 101.78, 1946 => 101.78, 1945 => 101.78, 1944 => 101.78, 
        1943 => 119.93, 
        1942 => 140.83, 
        1941 => 163.12, 
        1940 => 183.66, 
        // 1937 a 1939
        1939 => 218.25, 1938 => 218.25, 1937 => 218.25, 
        // 1925 a 1936
        1936 => 224.73, 1935 => 224.73, 1934 => 224.73, 1933 => 224.73, 1932 => 224.73, 1931 => 224.73, 
        1930 => 224.73, 1929 => 224.73, 1928 => 224.73, 1927 => 224.73, 1926 => 224.73, 1925 => 224.73, 
        1924 => 260.75, 
        1923 => 309.74, 
        1922 => 506.14, 
        1921 => 683.44, 
        1920 => 1047.47, 
        1919 => 1585.26, 
        1918 => 2068.48, 
        1917 => 2899.18, 
        1916 => 3631.71, 
        1915 => 4437.01, 
        // 1911 a 1914
        1914 => 4987.11, 1913 => 4987.11, 1912 => 4987.11, 1911 => 4987.11, 
        // 1904 a 1910
        1910 => 5199.71, 1909 => 5199.71, 1908 => 5199.71, 1907 => 5199.71, 1906 => 5199.71, 1905 => 5199.71, 1904 => 5199.71, 
        // Até 1903 (Cobre o limite min:1900 da validação)
        1903 => 5585.78, 1902 => 5585.78, 1901 => 5585.78, 1900 => 5585.78, 
    ];

    private const IRS_BRACKETS_2025 = [
        ['limit' => 8059,  'rate' => 0.1250, 'deduction' => 0.00],
        ['limit' => 12160, 'rate' => 0.1600, 'deduction' => 282.07],
        ['limit' => 17233, 'rate' => 0.2150, 'deduction' => 950.87],
        ['limit' => 22306, 'rate' => 0.2440, 'deduction' => 1450.63],
        ['limit' => 28400, 'rate' => 0.3140, 'deduction' => 3011.65],
        ['limit' => 41629, 'rate' => 0.3490, 'deduction' => 4005.65],
        ['limit' => 44987, 'rate' => 0.4310, 'deduction' => 7418.98],
        ['limit' => 83696, 'rate' => 0.4460, 'deduction' => 8093.79],
        ['limit' => INF,   'rate' => 0.4800, 'deduction' => 10939.45],
    ];

    public function calculate(array $data): array
    {
        $saleValue = (float) $data['sale_value'];
        $acquisitionValue = (float) $data['acquisition_value'];
        $acquisitionYear = (int) $data['acquisition_year'];
        $expenses = (float) ($data['expenses_total'] ?? 0);

        // Aplicação do coeficiente
        // Se o ano não existir na tabela (ex: anterior a 1900), usa o valor limite de 1903 (5585.78)
        $coefficient = self::COEFFICIENTS[$acquisitionYear] ?? ($acquisitionYear < 1900 ? 5585.78 : 1.00);
        
        $updatedAcquisitionValue = $acquisitionValue * $coefficient;

        $grossGain = $saleValue - $updatedAcquisitionValue - $expenses;

        // --- ISENÇÕES TOTAIS ---

        // Venda ao Estado
        if (($data['sold_to_state'] ?? 'Não') === 'Sim') {
            return $this->buildResult($saleValue, $updatedAcquisitionValue, $expenses, 0, $grossGain, 0, 0, $coefficient, 'Isento (Venda ao Estado)');
        }

        // Aquisição antes de 1989
        if ($acquisitionYear < 1989) {
            return $this->buildResult($saleValue, $updatedAcquisitionValue, $expenses, 0, $grossGain, 0, 0, $coefficient, 'Isento (Anterior a 1989)');
        }

        if ($grossGain <= 0) {
            return $this->buildResult($saleValue, $updatedAcquisitionValue, $expenses, 0, $grossGain, 0, 0, $coefficient, 'Sem Mais-Valia');
        }

        // --- CÁLCULO DA MATÉRIA COLETÁVEL ---

        $taxableGainBase = $grossGain;
        $amountToExclude = 0.0; // Valor total a abater à mais-valia (Reinvestimento + Amortização)

        $isHPP = ($data['hpp_status'] ?? 'Não') === 'Sim';

        // 1. Reinvestimento em NOVA habitação (Apenas se vendeu HPP)
        if ($isHPP && ($data['reinvest_intention'] ?? 'Não') === 'Sim') {
            $amountToExclude += (float) ($data['reinvestment_amount'] ?? 0);
        }

        // 2. Amortização de Crédito Habitação (Válido para HPP e Secundários - Norma Mais Habitação)
        // Se o user preencheu, assumimos que é elegível
        if (($data['amortize_credit'] ?? 'Não') === 'Sim') {
            $amountToExclude += (float) ($data['amortization_amount'] ?? 0);
        }

        // 3. Reformados (PPR/Seguros) - Apenas se vendeu HPP
        if ($isHPP && ($data['retired_status'] ?? 'Não') === 'Sim') {
             // O formulário não tem campo específico "valor investido em PPR", 
             // mas assumimos que entra no "reinvestment_amount" se a lógica for simplificada.
        }

        // Aplicar a exclusão proporcional
        if ($amountToExclude >= $saleValue) {
            $taxableGainBase = 0; // Tudo isento
        } elseif ($amountToExclude > 0) {
            // Fórmula: Mais-Valia Tributável = Mais-Valia Total * (1 - (Valor Reinvestido / Valor Realização))
            $ratio = ($saleValue - $amountToExclude) / $saleValue;
            $taxableGainBase = $grossGain * $ratio;
        }

        // Regra dos 50% (Englobamento)
        $taxableGain = $taxableGainBase * 0.5;

        // --- CÁLCULO IMPOSTO ---

        $annualIncome = (float) ($data['annual_income'] ?? 0);
        $isJoint = ($data['joint_tax_return'] ?? 'Não') === 'Sim';
        
        $estimatedTax = $this->calculateEstimatedTax($taxableGain, $annualIncome, $isJoint);

        return $this->buildResult(
            $saleValue,
            $updatedAcquisitionValue,
            $expenses,
            $amountToExclude, // Passamos o total abatido para mostrar na View
            $grossGain,
            $taxableGain,
            $estimatedTax,
            $coefficient,
            'Tributável'
        );
    }

    private function calculateEstimatedTax(float $gain, float $income, bool $isJoint): float
    {
        if ($gain <= 0) return 0;

        $incomeBase = $isJoint ? ($income / 2) : $income;
        $incomeWithGain = $isJoint ? (($income + $gain) / 2) : ($income + $gain);

        // 1. IRS Normal
        $taxBase = $this->calculateIRS($incomeBase);
        $taxFinal = $this->calculateIRS($incomeWithGain);
        $irsNormal = max(0, $taxFinal - $taxBase);

        // 2. Taxa Solidariedade (>80k)
        $solidarityTax = $this->calculateSolidarityTax($incomeWithGain);
        $solidarityBase = $this->calculateSolidarityTax($incomeBase);
        $solidarityDiff = max(0, $solidarityTax - $solidarityBase);

        $totalPerPerson = $irsNormal + $solidarityDiff;

        return $isJoint ? $totalPerPerson * 2 : $totalPerPerson;
    }

    private function calculateIRS(float $income): float
    {
        if ($income <= 0) return 0;

        foreach (self::IRS_BRACKETS_2025 as $bracket) {
            if ($income <= $bracket['limit']) {
                return ($income * $bracket['rate']) - $bracket['deduction'];
            }
        }
        // Fallback (acima do último escalão)
        return ($income * 0.48) - 10939.45;
    }

    private function calculateSolidarityTax(float $income): float
    {
        if ($income <= 80000) return 0.0;

        $tax = 0.0;
        // Nível 1: 80k a 250k (2.5%)
        if ($income > 80000) {
            $taxable = min($income, 250000) - 80000;
            $tax += $taxable * 0.025;
        }
        // Nível 2: > 250k (5%)
        if ($income > 250000) {
            $taxable = $income - 250000;
            $tax += $taxable * 0.05;
        }
        return $tax;
    }

    private function buildResult($sale, $acqUpd, $exp, $reinvest, $gross, $taxable, $tax, $coef, $status): array
    {
        return [
            'sale_fmt' => number_format($sale, 2, ',', '.'),
            'coefficient' => number_format($coef, 2, ',', '.'),
            'acquisition_updated_fmt' => number_format($acqUpd, 2, ',', '.'),
            'expenses_fmt' => number_format($exp, 2, ',', '.'),
            'reinvestment_fmt' => number_format($reinvest, 2, ',', '.'),
            'gross_gain_fmt' => number_format($gross, 2, ',', '.'),
            'taxable_gain_fmt' => number_format($taxable, 2, ',', '.'),
            'estimated_tax_fmt' => number_format($tax, 2, ',', '.'),
            'status' => $status,
            'raw_tax' => $tax,
            'raw_gross' => $gross
        ];
    }
}