<?php

use Illuminate\Support\Facades\Cache;
use League\CommonMark\Extension\Autolink\AutolinkExtension;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\Extension\TableOfContents\TableOfContentsExtension;
use League\CommonMark\MarkdownConverter;
use Phiki\CommonMark\PhikiExtension;
use Phiki\Theme\Theme;
use Spatie\LaravelSettings\Models\SettingsProperty;

if (! function_exists('content_block')) {
    function content_block($key)
    {
        $locale = app()->getLocale();

        return Cache::remember("content_block_{$key}_{$locale}", 60, function () use ($key) {
            $block = \App\Models\ContentBlock::query()->where('name', $key)->first();

            return $block ? $block->content : '';
        });
    }
}

/**
 * @throws \League\CommonMark\Exception\CommonMarkException
 * @throws Exception
 */
if (! function_exists('markdown')) {
    /**
     * @throws \League\CommonMark\Exception\CommonMarkException
     * @throws Exception
     */
    function markdown(string $doc): string
    {
        $config = [
            'renderer' => [
                'block_separator' => "\n",
                'inner_separator' => "\n",
                'soft_break' => '<p>',
            ],
            'commonmark' => [
                'enable_em' => true,
                'enable_strong' => true,
                'use_asterisk' => true,
                'use_underscore' => true,
                'unordered_list_markers' => ['-', '*', '+'],
            ],
            'html_input' => 'escape',
            'heading_permalink' => [
                'html_class' => 'heading-permalink',
                'id_prefix' => '',
                'apply_id_to_heading' => true,
                'heading_class' => 'font-bold',
                'fragment_prefix' => 'content',
                'insert' => 'before',
                'min_heading_level' => 1,
                'max_heading_level' => 6,
                'title' => 'Permalink',
                'symbol' => '# ',
                'aria_hidden' => true,
            ],
            'table_of_contents' => [
                'html_class' => 'tocs',
                'position' => 'before-headings',
                'style' => 'bullet',
                'min_heading_level' => 1,
                'max_heading_level' => 6,
                'normalize' => 'relative',
                'placeholder' => null,
            ],
            'table' => [
                'wrap' => [
                    'enabled' => true,
                    'tag' => 'div',
                    'attributes' => [
                        'class' => 'content-table',
                    ],
                ],
            ],
        ];
        $environment = new League\CommonMark\Environment\Environment($config);
        $environment
            ->addExtension(new CommonMarkCoreExtension)
            ->addExtension(new TableOfContentsExtension)
            ->addExtension(new HeadingPermalinkExtension)
            ->addExtension(new AutolinkExtension)
            ->addExtension(new TableExtension)
            ->addExtension(new PhikiExtension(Theme::GithubDark));
        $markdown = new MarkdownConverter($environment);

        return $markdown->convert($doc)->getContent();
    }
}

if (! function_exists('setting')) {
    function setting($key, $default = null)
    {
        [$group, $name] = explode('.', $key);

        $setting = SettingsProperty::query()
            ->where('group', $group)
            ->where('name', $name)
            ->first('payload');
        if (! $setting) {
            return $default;
        }

        return json_decode($setting->getAttribute('payload'));
    }
}

// create function get domain from email
if (!function_exists('get_domain_from_email')) {
    function get_domain_from_email(string $email): ?string
    {
        $parts = explode('@', $email);
        if (count($parts) !== 2) {
            return null; // Invalid email format
        }

        return $parts[1];
    }
}
