<tr>
    <td>
        <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td class="content-cell" align="center">
                    {{ Illuminate\Mail\Markdown::parse($slot) }}

                    {{ Illuminate\Mail\Markdown::parse(<<<HTML
                    <a href="https://tempmail.id.vn/p/api" class="hover-underline" style="text-decoration: none">API</a> &bull;
                    <a href="https://tempmail.id.vn/blog" class="hover-underline" style="color: #3b82f6; text-decoration: none">Blog</a> &bull;
                    <a href="https://tempmail.id.vn/p/faq" class="hover-underline" style="color: #3b82f6; text-decoration: none">FAQ\'s</a> &bull;
                    <a href="https://tempmail.id.vn/p/privacy-policy" class="hover-underline" style="color: #3b82f6; text-decoration: none">Privacy Policy</a>
                    HTML) }}
                </td>
            </tr>
        </table>
    </td>
</tr>
