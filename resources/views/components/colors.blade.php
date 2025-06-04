<style>
    @if ($primaryBgColor)
        .bg-red-700 {
            background-color: {{ $primaryBgColor }} !important;
        }
    @endif
    @if ($primaryTextColor)
        .text-gray-900 {
            color: {{ $primaryTextColor }} !important;
        }
    @endif
    @if ($secondaryBgColor)
        .bg-black {
            background-color: {{ $secondaryBgColor }} !important;
        }
    @endif
    @if ($secondaryTextColor)
        .text-gray-700 {
            color: {{ $secondaryTextColor }} !important;
        }
    @endif
    @if ($hoverBgColor)
        .bg-red-800 {
            background-color: {{ $hoverBgColor }} !important;
        }
    @endif
    @if ($hoverTextColor)
        .text-white {
            color: {{ $hoverTextColor }} !important;
        }
    @endif
</style>
