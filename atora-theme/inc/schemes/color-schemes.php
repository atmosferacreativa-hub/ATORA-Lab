<?php
/**
 * Color Schemes Manager
 */

class Atora_Color_Schemes {
    
    public static function get_schemes() {
        return [
            'indigo' => [
                'name' => 'Indigo Professional',
                'primary' => '#4F46E5',
                'secondary' => '#10B981',
                'accent' => '#F59E0B',
            ],
            'ocean' => [
                'name' => 'Ocean Blue',
                'primary' => '#0EA5E9',
                'secondary' => '#06B6D4',
                'accent' => '#14B8A6',
            ],
            'forest' => [
                'name' => 'Forest Green',
                'primary' => '#10B981',
                'secondary' => '#059669',
                'accent' => '#34D399',
            ],
            'sunset' => [
                'name' => 'Sunset Orange',
                'primary' => '#F59E0B',
                'secondary' => '#EF4444',
                'accent' => '#F97316',
            ],
            'royal' => [
                'name' => 'Royal Purple',
                'primary' => '#8B5CF6',
                'secondary' => '#A78BFA',
                'accent' => '#C084FC',
            ],
            'cherry' => [
                'name' => 'Cherry Red',
                'primary' => '#EF4444',
                'secondary' => '#F87171',
                'accent' => '#FCA5A5',
            ],
            'teal' => [
                'name' => 'Teal Modern',
                'primary' => '#14B8A6',
                'secondary' => '#06B6D4',
                'accent' => '#22D3EE',
            ],
            'pink' => [
                'name' => 'Pink Creative',
                'primary' => '#EC4899',
                'secondary' => '#F472B6',
                'accent' => '#F9A8D4',
            ],
            'amber' => [
                'name' => 'Amber Warm',
                'primary' => '#F59E0B',
                'secondary' => '#FBBF24',
                'accent' => '#FCD34D',
            ],
            'gray' => [
                'name' => 'Monochrome Gray',
                'primary' => '#6B7280',
                'secondary' => '#9CA3AF',
                'accent' => '#D1D5DB',
            ],
            // ── Atora Brand Palette (from logo: steel-blue ring + amber center) ──
            'atora' => [
                'name' => 'Atora Brand',
                'primary' => '#4A7CB5',
                'secondary' => '#F0A020',
                'accent' => '#E8891A',
            ],
            'atora-dark' => [
                'name' => 'Atora Dark',
                'primary' => '#2C5F8A',
                'secondary' => '#F0A020',
                'accent' => '#D4781A',
            ],
            'atora-amber' => [
                'name' => 'Atora Amber',
                'primary' => '#F0A020',
                'secondary' => '#4A7CB5',
                'accent' => '#E8891A',
            ],
        ];
    }
    
    public static function get_scheme($scheme_id) {
        $schemes = self::get_schemes();
        return isset($schemes[$scheme_id]) ? $schemes[$scheme_id] : $schemes['atora'];
    }
}
