<x-lareon::admin-editor :action="route('admin.users.store')" :hasTab="false">
    @section('title', __('lareon::global.crud.titles.create',['attribute'=>__('user')]))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.users.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('users')])" color="index"/>
    @endsection
    @section('form')
        <div class="space-y-6">
            <x-lareon::editor.input :required="true" labelPosition="start" :label="__('title')" name="title" :value="old('title')" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('title') , 'item'=>__('page')])"/>
            <x-lareon::editor.input-slug :required="true" labelPosition="start" :label="__('slug')" name="slug" :value="old('slug')" :placeholder="__('lareon::global.placeholders.unique.two',['attribute'=>__('slug') , 'item'=>__('page')])"/>
        </div>
      <div class="grid gap-6 xl:grid-cols-2">
          <x-lareon::box type="y">
              <fieldset class="fieldset space-y-6">
                  <legend class="legend">{{__('content')}}</legend>

                  <div class="space-y-6">
                      <x-lareon::editor.input-textarea :required="false" :label="__('excerpt')" name="excerpt" :placeholder="__('lareon::global.placeholders.write.one',['attribute'=>__('excerpt')])">{!! old('excerpt') !!}</x-lareon::editor.input-textarea>
                      <x-lareon::editor.input-editor rows="9" :required="false" :label="__('body')" name="body" :placeholder="__('lareon::global.placeholders.write.one',['attribute'=>__('body')])">{!! old('body') !!}</x-lareon::editor.input-editor>
                  </div>
              </fieldset>
          </x-lareon::box>
          <x-lareon::box type="y">
              <fieldset class="fieldset space-y-6">
                  <legend class="legend">{{__('content')}}</legend>

                  <div class="space-y-6">
                      <x-lareon::editor.input-textarea :required="false" :label="__('excerpt')" name="excerpt" :placeholder="__('lareon::global.placeholders.write.one',['attribute'=>__('excerpt')])">{!! old('excerpt') !!}</x-lareon::editor.input-textarea>
                      <x-lareon::editor.input-editor rows="9" :required="false" :label="__('body')" name="body" :placeholder="__('lareon::global.placeholders.write.one',['attribute'=>__('body')])">{!! old('body') !!}</x-lareon::editor.input-editor>
                  </div>
              </fieldset>
          </x-lareon::box>
      </div>

    @endsection
    @section('aside')
    @endsection
</x-lareon::admin-editor>
