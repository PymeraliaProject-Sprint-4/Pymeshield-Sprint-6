<template>
    <header class="hidden md:block">
        <div class="flex justify-between items-center p-3">
            <div class="font-semibold text-xl ml-2"><a href="/home"><img class="inline-block mr-2 w-16"
                        src="/img/logo_pymeshield.png" alt="Logo">PymeShield</a>
            </div>
            <div class="ml-4 flex justify-end items-center md:ml-6">

                <Menu as="div" class="relative ml-3">
                    <div>
                        <MenuButton
                            class="rounded-full bg-gray-200 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                            <span class="sr-only">Abrir menú de usuario</span>
                            <LanguageIcon class="h-8 w-8 text-blue-500" aria-hidden="true" />
                        </MenuButton>
                    </div>
                    <transition enter-active-class="transition ease-out duration-100"
                        enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100"
                        leave-active-class="transition ease-in duration-75"
                        leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                        <MenuItems
                            class="absolute right-0 z-10 mt-2 w-40 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                            <MenuItem v-for="item in languaje" :key="languaje.name" v-slot="{ active }">
                            <a :href="item.href"
                                :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">
                                <img :src="item.flag" class="inline-block mr-2 h-5 w-5 object-cover rounded-full"
                                    aria-hidden="true" />{{
                                        item.name
                                    }}</a>
                            </MenuItem>
                        </MenuItems>
                    </transition>
                </Menu>

                <!-- Profile dropdown -->
                <Menu as="div" class="relative ml-3">
                    <div>
                        <MenuButton
                            class="flex max-w-xs items-center rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                            <span class="sr-only">{{ $t('user-menu') }}</span>
                            <vue-avatar v-if="!user.profile_image" :size="40" :username="user.name + user.last_name" />
                            <vue-avatar v-else :size="40" :img-src="'/img/profile_images/' + user.profile_image" />
                        </MenuButton>
                    </div>
                    <transition enter-active-class="transition ease-out duration-100"
                        enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100"
                        leave-active-class="transition ease-in duration-75"
                        leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                        <MenuItems
                            class="absolute right-0 z-10 mt-2 w-60 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                            <MenuItem v-for="item in userNavigation" :key="item.name" v-slot="{ active }">
                            <a :href="item.href" :target="item.target"
                                :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">
                                <component :is="item.icon" class="inline-block mr-1 h-5 w-5" aria-hidden="true" />{{
                                    item.name
                                }}
                            </a>
                            </MenuItem>
                        </MenuItems>
                    </transition>
                </Menu>
            </div>
        </div>
    </header>
    <div>
        <Disclosure as="nav" class="bg-orange-500 md:bg-orange-400" v-slot="{ open }">
            <div class="mx-start px-4 lg:px-8">
                <div class="flex items-center md:justify-between py-3 md:py-0">
                    <div class="flex items-center">
                        <div class="hidden md:block">
                            <div class="flex items-baseline">
                                <a v-for="item in navigation" :key="item.name" :href="item.href"
                                    :class="[item.current ? 'bg-gray-900 text-white' : 'text-black hover:bg-gray-700 hover:text-white', 'px-3 py-2 text-sm font-medium']"
                                    :aria-current="item.current ? 'page' : undefined">
                                    <component :is="item.icon" class="inline-block mr-1 h-5 w-5" aria-hidden="true" />{{
                                        item.name
                                    }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="-mr-2 flex md:hidden items-center">
                        <!-- Mobile menu button -->
                        <DisclosureButton
                            class="inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                            <span class="sr-only">{{ $t('menu') }}</span>
                            <Bars3Icon v-if="!open" class="block h-6 w-6" aria-hidden="true" />
                            <XMarkIcon v-else class="block h-6 w-6" aria-hidden="true" />
                        </DisclosureButton>
                        <div class="font-semibold text-xl ml-2"><a href="/home">PymeShield</a></div>
                    </div>
                </div>
            </div>

            <DisclosurePanel class="md:hidden bg-orange-400">
                <div class="space-y-1 px-2 pt-2 pb-3 sm:px-3">
                    <DisclosureButton v-for="item in navigation" :key="item.name" as="a" :href="item.href"
                        :class="[item.current ? 'bg-gray-900 text-white' : 'text-gray-900 hover:bg-gray-700 hover:text-white', 'block px-3 py-2 rounded-md text-base font-medium']"
                        :aria-current="item.current ? 'page' : undefined">
                        <component :is="item.icon" class="inline-block mr-2 h-6 w-6" aria-hidden="true" />{{
                            item.name
                        }}
                    </DisclosureButton>
                </div>
                <div class="border-t border-gray-700 pt-4 pb-3">
                    <div class="relative flex flex-col ml-auto px-5">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <vue-avatar v-if="!user.profile_image" :size="40" :username="user.name + user.last_name" />
                                <vue-avatar v-else :size="40" :img-src="'/img/profile_images/' + user.profile_image" />
                            </div>
                            <div class="ml-3">
                                <div class="text-base font-medium leading-none text-gray-900">{{ user.name + ' ' +
                                    user.last_name }}</div>
                                <div class="text-sm font-medium leading-none text-gray-700">{{ user.email }}</div>
                            </div>
                        </div>
                        <div class="flex mt-2">
                            <Menu as="div">
                                <div>
                                    <MenuButton
                                        class="rounded-full bg-white p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                                        <span class="sr-only">Abrir menú de usuario</span>
                                        <LanguageIcon class="h-8 w-8 text-blue-500" aria-hidden="true" />
                                    </MenuButton>
                                </div>
                                <transition enter-active-class="transition ease-out duration-100"
                                    enter-from-class="transform opacity-0 scale-95"
                                    enter-to-class="transform opacity-100 scale-100"
                                    leave-active-class="transition ease-in duration-75"
                                    leave-from-class="transform opacity-100 scale-100"
                                    leave-to-class="transform opacity-0 scale-95">
                                    <MenuItems
                                        class="absolute z-10 mt-2 w-40 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                        <MenuItem v-for="item in languaje" :key="languaje.name" v-slot="{ active }">
                                        <a :href="item.href"
                                            :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">
                                            <img :src="item.flag"
                                                class="inline-block mr-2 h-5 w-5 object-cover rounded-full"
                                                aria-hidden="true" />{{
                                                    item.name
                                                }}</a>
                                        </MenuItem>
                                    </MenuItems>
                                </transition>
                            </Menu>
                        </div>
                    </div>
                    <div class="mt-3 space-y-1 px-2">
                        <DisclosureButton v-for="item in userNavigation" :key="item.name" as="a" :href="item.href" :target="item.target"
                            class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-700 hover:text-white">
                            <component :is="item.icon" class="inline-block mr-1 h-5 w-5" aria-hidden="true" />
                            {{ item.name }}
                        </DisclosureButton>
                    </div>
                </div>
            </DisclosurePanel>
        </Disclosure>
    </div>
</template>

<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { AcademicCapIcon, Bars3Icon, CalendarDaysIcon, CurrencyEuroIcon, ViewColumnsIcon, DocumentDuplicateIcon, HomeIcon, QrCodeIcon, XMarkIcon, LanguageIcon, IdentificationIcon, WrenchScrewdriverIcon, ArrowLeftOnRectangleIcon, QueueListIcon, LifebuoyIcon } from '@heroicons/vue/24/outline'
import VueAvatar from "@webzlodimir/vue-avatar";
import "@webzlodimir/vue-avatar/dist/style.css";

</script>
<script>
export default {
    data() {
        return {
            user: window.authUser,
            navigation: [
                { name: this.$t('navbar.home'), href: '/home', current: false, icon: HomeIcon },
                { name: this.$t('navbar.questionnaires'), href: '/audit', current: false, icon: QueueListIcon },
                { name: this.$t('navbar.reports'), href: '/report/client', current: false, icon: DocumentDuplicateIcon },
                { name: this.$t('formation'), href: '/course/client', current: false, icon: AcademicCapIcon },
                { name: this.$t('budgets'), href: '/show_budgets_client', current: false, icon: CurrencyEuroIcon },
                { name: this.$t('kanban'), href: '/kanban', current: false, icon: ViewColumnsIcon },
                { name: this.$t('navbar.gantt'), href: '/gantt', current: false, icon: CalendarDaysIcon },
                { name: this.$t('inventory'), href: '/inventario', current: false, icon: QrCodeIcon },
            ],
        };
    },
    computed: {
        userNavigation() {
            if (this.user.type === 'admin') {
                return [
                    { name: this.$t('profile'), href: '/Personal_Profile', icon: IdentificationIcon },
                    { name: this.$t('help'), href: '/manuals/manual_usuari.pdf', target: '_blank', icon: LifebuoyIcon },
                    { name: this.$t('admin-mode'), href: '/admin', icon: WrenchScrewdriverIcon },
                    { name: this.$t('sign-out'), href: '/logout', icon: ArrowLeftOnRectangleIcon, class: 'btn btn-danger bg-red-500 hover:bg-red-600', style: "background-color: #EF4444;" },
                ];
            } else {
                return [
                    { name: this.$t('profile'), href: '/Personal_Profile', icon: IdentificationIcon },
                    { name: this.$t('help'), href: '/manuals/manual_usuari.pdf', target: '_blank', icon: LifebuoyIcon },
                    { name: this.$t('sign-out'), href: '/logout', icon: ArrowLeftOnRectangleIcon, class: 'btn btn-danger bg-red-500 hover:bg-red-600', style: "background-color: #EF4444;" },
                ];
            }
        },
        languaje() {
            return [
                { name: this.$t('english'), href: '/language/en', flag: '/img/english.svg' },
                { name: this.$t('spanish'), href: '/language/es', flag: '/img/spanish.svg' },
                { name: this.$t('catalan'), href: '/language/ca', flag: '/img/catalan.svg' },
            ];

        }
    },
    mounted() {
        const currentUrl = window.location.pathname;
        this.navigation.forEach((item) => {
            if (currentUrl.startsWith(item.href)) {
                item.current = true;
            }
        });
    },
}
</script>
