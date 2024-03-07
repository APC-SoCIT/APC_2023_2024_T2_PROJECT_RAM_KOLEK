import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/Faculty/**/*.php',
        './resources/views/filament/faculty/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
}
