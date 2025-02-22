import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './vendor/awcodes/filament-table-repeater/resources/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                white: '#F6F5F3',
                platinum: '#F6F5F0',
                moonlight: '#F6F5F3',
                'translucent': {
                    light: 'rgba(246, 245, 243 0.5)',
                    DEFAULT: 'rgba(255, 255, 255, 0.5)',
                    dark: 'rgba(25, 25, 25, 0.5)',
                },
            },
        }
    }
}
