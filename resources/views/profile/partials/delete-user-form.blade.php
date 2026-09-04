<section class="space-y-6">
    <header>
        <h2 class="font-serif text-lg font-bold text-red-900">
            حذف الحساب
        </h2>

        <p class="mt-1 text-xs text-red-700/80 leading-5">
            بمجرد حذف حسابك، سيتم حذف جميع موارده وبياناته بشكل نهائي. قبل حذف حسابك، يرجى حفظ أي بيانات ترغب بالاحتفاظ بها.
        </p>
    </header>

    <button
        type="button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="oasis-button !min-h-[42px] !px-5 !text-xs !bg-red-700 hover:!bg-red-800 text-white"
    >
        حذف الحساب نهائيًا
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 sm:p-8" dir="rtl">
            @csrf
            @method('delete')

            <h2 class="font-serif text-lg font-bold text-oasis-house">
                هل أنت متأكد من رغبتك في حذف الحساب؟
            </h2>

            <p class="oasis-copy mt-2 text-xs leading-5">
                بمجرد حذف حسابك، سيتم مسح كافة البيانات بصورة دائمة. يرجى إدخال كلمة المرور لتأكيد رغبتك في حذف الحساب.
            </p>

            <div class="mt-6">
                <label for="password" class="oasis-label sr-only">كلمة المرور</label>

                <input
                    id="password"
                    name="password"
                    type="password"
                    class="oasis-input mt-1 block w-full sm:w-3/4 {{ $errors->userDeletion->has('password') ? '!border-red-400 focus:!border-red-500' : '' }}"
                    placeholder="كلمة المرور الحالية للتأكيد"
                >

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex flex-row-reverse items-center justify-end gap-3 border-t border-black/5 pt-4">
                <button type="submit" class="oasis-button !min-h-[42px] !px-5 !text-xs !bg-red-700 hover:!bg-red-800 text-white">
                    حذف الحساب
                </button>

                <button type="button" class="oasis-button oasis-button-outline !min-h-[42px] !px-5 !text-xs" x-on:click="$dispatch('close')">
                    إلغاء
                </button>
            </div>
        </form>
    </x-modal>
</section>
