<?php

if (!function_exists('classify_vark')) {
    function classify_vark(array $answers): array
    {
        $scores = ['V' => 0, 'A' => 0, 'R' => 0, 'K' => 0];
        foreach ($answers as $ans) {
            if (isset($scores[strtoupper($ans)])) {
                $scores[strtoupper($ans)]++;
            }
        }

        arsort($scores);
        $keys = array_keys($scores);
        $highest = $scores[$keys[0]];
        $second = $scores[$keys[1]] ?? 0;
        $delta = $highest - $second;

        $typeMap = [
            'V' => 'Visual',
            'A' => 'Aural',
            'R' => 'Read/Write',
            'K' => 'Kinestetik'
        ];

        if ($delta > 2) {
            return [
                'type'     => $keys[0],
                'category' => 'Unimodal',
                'delta'    => $delta,
                'scores'   => $scores,
                'label'    => $typeMap[$keys[0]]
            ];
        } else {
            return [
                'type'     => 'M',
                'category' => 'Multimodal',
                'delta'    => $delta,
                'scores'   => $scores,
                'label'    => 'Multimodal (Campuran)'
            ];
        }
    }
}