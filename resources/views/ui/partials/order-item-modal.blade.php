<div 
            x-data="{ 
                selected: {
                    id: null,
                    child: '',
                    parent: '',
                    qrChild: '',
                    qrGuardian: '',
                }
            }"

            x-on:open-order-modal.window="
                selected = $event.detail;
                $dispatch('open-modal', 'order-item-modal');
            "
>
    <x-breeze-modal name="order-item-modal" :show="false" maxWidth="4xl" focusable>
        <div class="flex flex-col">
            <div class="py-2 px-6 mt-2">
                <h2 class="font-semibold text-gray-900">Register/Edit QR</h2>
            </div>
            <div class="h-px bg-gray-600 w-full"></div>
            <div class="py-2 px-6">
                <form 
                    method="POST"
                    :action="`/admin/order-item/${selected.id}`"
                    class="space-y-4"
                >
                    @csrf
                    @method('PUT')

                    <div class="flex flex-wrap gap-2">
                        <p class="flex-1">
                            <span class="text-2xl font-semibold" x-text="selected.child"></span>
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-4">
                        <div class="flex-1">
                            <label for="qr_child" class="block text-gray-900 font-semibold text-sm">
                                QR Child
                            </label>
                            <div class="mt-2">
                                <input
                                type="text"
                                x-model="selected.qrChild"
                                name="qr_child"
                                class="block w-full rounded-md py-1.5 px-2 ring-1 ring-inset ring-gray-400 focus:text-gray-800"
                                />
                            </div>
                        </div>
                        <div class="flex-1">
                            <label for="qr_guardian" class="block text-gray-900 font-semibold text-sm">
                                QR Guardian
                            </label>
                            <div class="mt-2">
                                <input
                                type="text"
                                x-model="selected.qrGuardian"
                                name="qr_guardian"
                                class="block w-full rounded-md py-1.5 px-2 ring-1 ring-inset ring-gray-400 focus:text-gray-800"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-2">
                        <button type="button" x-on:click="$dispatch('close')"
                            class="px-4 py-2 bg-gray-300 rounded-lg uppercase hover:opacity-80">
                            Cancel
                        </button>

                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white uppercase rounded-lg hover:opacity-80">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </x-breeze-modal>
</div>