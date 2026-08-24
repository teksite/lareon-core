<x-lareon::panel-layout>
    @section('title', __('lareon::global.crud.titles.edit',['attribute'=>__('profile')]) . "($user->fullname)")

    <div class="inner-content">
        <ul>
            @foreach($notifications as $notification)
                <li>
                   <div class="y-box">

                   </div>
                </li>
            @endforeach
        </ul>
    </div>
</x-lareon::panel-layout>
