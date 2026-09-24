@props(['value'=>[]])
<h3>
    {{__('send admin notification via')}}
</h3>
<x-lareon::editor.input :label="__('email')" :value="$value['emails'] ?? '' " name="announcements[emails]" :placeholder="__('separate with ,')" dir="ltr" type="text"/>
<x-lareon::editor.input :label="__('phone')" :value="$value['phones'] ?? '' " name="announcements[phones]" :placeholder="__('separate with ,')" dir="ltr" type="text"/>
<x-lareon::editor.input :label="__('url')" :value="$value['urls'] ?? '' " name="announcements[urls]" :placeholder="__('separate with ,')" dir="ltr" type="text"/>
<x-lareon::editor.input :label="__('telegram id')" :value="$value['telegram_ids'] ?? '' " name="announcements[telegram_ids]" :placeholder="__('separate with ,')" dir="ltr" type="text"/>
