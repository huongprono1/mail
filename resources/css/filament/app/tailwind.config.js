import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/App/**/*.php',
        './resources/views/filament/app/**/*.blade.php',
        './resources/views/components/**/*.blade.php',
        './resources/views/components/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './vendor/awcodes/filament-curator/resources/**/*.blade.php',
    ],
    safelist: [
        'mt-8', 'mb-8', 'text-4xl', 'text-xl', 'ps-6', 'mb-2', 'mt-4', 'grid-cols-2', 'grid-cols-1', '-top-3', '-right-3', 'bg-red-400', 'ring-red-500', 'text-yellow-400',
    ],
}
