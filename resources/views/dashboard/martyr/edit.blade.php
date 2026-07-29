<x-guest-layout>
    <h1 class="text-xl m-4 text-center">تعديل بيانات شهيد </h1>
<form class="grid gap-8" method="POST" action="{{ route('dashboard.martyr.update', $martyr) }}">        @csrf
    @method('PUT')
        <!-- national_id -->
        <div>
            <x-input-label for="national_id" :value="__('national_id')" />
            <x-text-input id="national_id" class="block mt-1 w-full" type="text" name="national_id"
                :value="$martyr->national_id" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('national_id')" class="mt-2" />
        </div>
        <!-- name_en -->
        <div>
            <x-input-label for="name_en" :value="__('Name in english')" />
            <x-text-input id="name_en" class="block mt-1 w-full" type="text" name="name_en" :value="$martyr->name_en"
                required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('name_en')" class="mt-2" />
        </div>
        <!-- name_ar -->
        <div>
            <x-input-label for="name_ar" :value="__('Name in arabic')" />
            <x-text-input id="name_ar" class="block mt-1 w-full" type="text" name="name_ar" :value="$martyr->name_ar"
                required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('name_ar')" class="mt-2" />
        </div>
        <!-- sex -->
        <div>
            <x-input-label for="sex" :value="__('sex')" />
            <select required name="sex" id="sex"
                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                required>
                <option @selected($martyr->sex === 'm') value="m">male</option>
                <option @selected($martyr->sex === 'f') value="f">female</option>
            </select>
            <x-input-error :messages="$errors->get('sex')" class="mt-2" />
        </div>
        <!-- age -->
        <div>
            <x-input-label for="age" :value="__('age')" />
            <x-text-input id="age" class="block mt-1 w-full" type="text" name="age" :value="$martyr->age" required
                autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('age')" class="mt-2" />
        </div>
        <!-- date_barth -->
        <div>
            <x-input-label for="date_barth" :value="__('date_barth')" />
            <x-text-input id="date_barth" class="block mt-1 w-full" type="date" name="date_barth"
                :value="$martyr->born" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('date_barth')" class="mt-2" />
        </div>

        <div class="flex justify-between">
            <x-primary-button class="ms-3">
                {{ __('Update') }}
            </x-primary-button>
            <a href="{{ route('dashboard.martyr.index') }}">Cancel</a>
        </div>

        </div>
    </form>
</x-guest-layout>
