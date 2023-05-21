<template>
    <header>
        <nav class="border-gray-200 bg-top-image px-1 md:px-6">
            <div
                class="max-w-screen-xl flex flex-wrap items-center align-top justify-between mx-auto p-1 pb-2 relative">
                <router-link to="/" class="flex items-center">
                    <img src="/img/ska.png" class="h-14" alt="ska logo"/>
                    <span
                        class="text-mild-white border-gray-100 self-center text-[26px] md:hidden lg:block ml-2.5 md:ml-5 font-benderbold whitespace-nowrap">skaenergy.ru</span>
                </router-link>
                <div class="flex items-center md:order-2 p-2 px-1 md:px-2">
                    <div v-if="!user" class="mr-2 md:hidden block text-base border-gray-100 border rounded-lg px-2 py-2 hover:bg-blue-900">
                        <router-link :to="{name:'login'}" class="text-gray-200">Войти</router-link>
                    </div>
                    <div v-if="user">
                        <button @click="isProfileOpened = !isProfileOpened" @focusout="isProfileOpened=false" type="button"
                                class="flex mr-3 text-sm md:hidden block border-2 bg-gray-800 rounded-full md:mr-0 focus:ring-4 focus:ring-gray-300"
                                id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown"
                                data-dropdown-placement="bottom">
                            <span class="sr-only">Open user menu</span>
                            <template v-if="user.thumb_url">
                            <img class="w-9 h-9 rounded-full" :src="user.thumb_url"
                                 alt="user photo">
                            </template>
                            <template v-else>
                            <div
                                 class="rounded-full font-semibold text-xl text-ska-blue bg-mild-white border text-center h-9 w-9 align-middle inline-block align-center">
                                <div class="align-center content-center py-1">{{ user.nick.substring(0, 1).toUpperCase() }}</div>
                            </div>
                            </template>
                        </button>
                    </div>
                    <!-- Dropdown menu -->
                    <div v-if="user" :class="isProfileOpened ? 'block' : 'hidden'"
                         class="z-50 absolute top-14 right-4 my-4 md:hidden block border border-gray-200 text-base list-none bg-mild-white divide-y divide-gray-100 rounded-lg shadow"
                         id="user-dropdown">
                        <div class="px-4 py-3 border-gray-100">
                            <span class="block text-sm text-gray-900">{{ user.nick }}</span>
                            <span class="block text-sm text-gray-500 truncate">{{ user.email }}</span>
                        </div>
                        <ul class="py-2" aria-labelledby="user-menu-button">
                            <li>
                                <a @mousedown="$router.push({name:'profile'})"
                                             class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Изменить
                                    профиль
                                </a>
                            </li>
                            <li>
                                <a href="#" @mousedown="tryToLogout"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Выйти</a>
                            </li>
                        </ul>
                    </div>
                    <button @click="isMenuOpened = !isMenuOpened" data-collapse-toggle="mobile-menu-2" type="button"
                            class="inline-flex items-center border-gray-100 border p-1 ml-0.5 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
                            aria-controls="mobile-menu-2" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                  clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <div :class="isMenuOpened ? 'block' : 'hidden'"
                     class="items-center mt-0.5 md:block justify-between w-full md:flex md:w-auto md:order-1"
                     id="mobile-menu-2">
                    <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 rounded-lg md:flex-row md:space-x-8 md:mt-0 md:border-0">
                        <li>
                            <a href="#"
                               class="block py-2 pl-3 pr-4 mr-1 font-benderregular text-mild-white rounded hover:bg-gray-100 hover:bg-opacity-40 md:hover:bg-transparent md:hover:text-blue-300 md:p-0">Матчи
                                СКА</a>
                        </li>
                        <li>
                            <a href="#"
                               class="block py-2 pl-3 pr-4 mr-1 font-benderregular text-mild-white rounded hover:bg-gray-100 hover:bg-opacity-40 md:hover:bg-transparent md:hover:text-blue-300 md:p-0">Первый
                                дивизион</a>
                        </li>
                        <li>
                            <a href="#"
                               class="block py-2 pl-3 pr-4 mr-1 font-benderregular text-mild-white rounded hover:bg-gray-100 hover:bg-opacity-40 md:hover:bg-transparent md:hover:text-blue-300 md:p-0">История
                                клуба</a>
                        </li>
                        <li>
                            <a href="#"
                               class="block py-2 pl-3 pr-4 mr-1 font-benderregular text-mild-white rounded hover:bg-gray-100 hover:bg-opacity-40 md:hover:bg-transparent md:hover:text-blue-300 md:p-0">Болельщики</a>
                        </li>
                        <li>
                            <a href="#"
                               class="block py-2 pl-3 pr-4 mr-1 font-benderregular text-mild-white rounded hover:bg-gray-100 hover:bg-opacity-40 md:hover:bg-transparent md:hover:text-blue-300 md:p-0">Фотоальбомы</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
</template>

<script>
export default {
    name: "MainLayout",
    data() {
        return {
            isProfileOpened: false,
            isMenuOpened: false,
            user: null,
        }
    },
    methods: {
        tryToLogout() {
            axios.post('/logout')
                .then(res => {
                    localStorage.removeItem('x_xsrf_token')
                    window.location.href = '/';
                })
        }

    }
}
</script>

<style scoped>

</style>
