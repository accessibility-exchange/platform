<table class="action" role="presentation" align="center" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center">
            <table role="presentation" width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center">
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td>
                                    <a class="button button-{{ $color ?? 'primary' }}" href="{{ $url }}"
                                        target="_blank" rel="noopener">{{ $slot }}</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
