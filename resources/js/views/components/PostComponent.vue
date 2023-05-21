<template>
    <div class="inline-block mb-1">
        <div class="relative inline-block">
            <div @mouseover="showUserModal(post.id)" @mouseleave="closeUserModal(post.id)"
                 v-if="post.user && post.user.thumb_url"
                 class="rounded-full border text-center h-6 w-6 mr-2 content-center align-middle">
                <img :src="post.user.thumb_url"
                     class="rounded-full border inline-block align-middle content-center"
                     alt="avatar">
            </div>
            <div @mouseover="showUserModal(post.id)" @mouseleave="closeUserModal(post.id)" v-else
                 class="rounded-full bg-ska-blue text-mild-white border cursor-pointer text-center h-6 w-6 align-middle mr-2">
                <span class="text-xs">{{ post.nick.substring(0, 1).toUpperCase() }}</span>
            </div>
            <div
                class="z-50 hidden w-[23rem] absolute top-2 left-0 my-4 border border-gray-200 text-base list-none bg-mild-white divide-y divide-gray-100 rounded-lg shadow"
                :id="`user-modal-${post.id}`">
                <div v-if="userInfo" class="p-4 text-gray-700 text-sm">
                    <div class="inline-block text-center align-middle">
                        <template v-if="userInfo.image_url">
                            <img :src="userInfo.image_url"
                                 class="rounded-full w-32 h-32 p-1 border inline-block align-middle content-center"
                                 alt="avatar">
                        </template>
                        <template v-else>
                            <img src="/img/no-avatar.png"
                                 class="rounded-full w-32 h-32 p-8 border inline-block align-middle content-center"
                                 alt="avatar">
                        </template>
                    </div>
                    <div class="inline-block ml-4 align-middle space-y-2">
                        <div
                            class="text-base leading-tight font-semibold text-ska-blue">
                            {{ userInfo.nick }}
                        </div>
                        <div v-if="userInfo.city"
                             class="text-base leading-tight pb-1">
                            ({{ userInfo.city }})
                        </div>
                        <div
                            class="leading-tight">
                            На сайте с: {{ userInfo.register_date }}
                        </div>
                        <div
                            class="leading-tight text-gray-700">
                            Сообщений: {{ userInfo.post_count }}
                        </div>
                    </div>
                    <div class="mt-2 space-y-2">
                        <div v-if="userInfo.country">
                            Страна: {{ userInfo.country }}
                        </div>
                        <div v-if="userInfo.favourite_clubs">
                            Любимые клубы: {{ userInfo.favourite_clubs }}
                        </div>
                        <div class="text-red-800" v-if="userInfo.banned_until">
                            В бане до: {{ userInfo.banned_until }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="align-middle inline-block">
            <a @mouseover="showUserModal(post.id)" @mouseleave="closeUserModal(post.id)"
               class="font-semibold text-ska-blue hover:underline cursor-pointer">{{ post.nick }}</a>
            <span class="text-gray-600 ml-1 hidden md:inline-block">{{ post.city ? `(${post.city})` : '' }}</span>
            <span
                class="text-gray-500 ml-2 tracking-tight">{{ post.date }}</span></div>
    </div>
    <div class="float-right inline-block relative">
        <svg @click="showActionsDropdown(post.id)"
             class="fill-gray-500 hover:fill-black hover:cursor-pointer hover:rounded-lg h-4 w-4"
             xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
             viewBox="0 0 32.055 32.055" xml:space="preserve">
                <g>
                    <path d="M3.968,12.061C1.775,12.061,0,13.835,0,16.027c0,2.192,1.773,3.967,3.968,3.967c2.189,0,3.966-1.772,3.966-3.967
		C7.934,13.835,6.157,12.061,3.968,12.061z M16.233,12.061c-2.188,0-3.968,1.773-3.968,3.965c0,2.192,1.778,3.967,3.968,3.967
		s3.97-1.772,3.97-3.967C20.201,13.835,18.423,12.061,16.233,12.061z M28.09,12.061c-2.192,0-3.969,1.774-3.969,3.967
		c0,2.19,1.774,3.965,3.969,3.965c2.188,0,3.965-1.772,3.965-3.965S30.278,12.061,28.09,12.061z"/>
                </g>
            </svg>
        <div
            class="z-50 hidden w-56 absolute top-2 right-0 my-4 border border-gray-200 text-base list-none bg-mild-white divide-y divide-gray-100 rounded-lg shadow"
            :id="`actions-dropdown-${post.id}`">
            <ul aria-labelledby="user-menu-button">
                <li>
                    <a @click="showLink(post.id)"
                       class="block px-4 py-3 text-sm leading-tight text-gray-700 hover:bg-gray-100">&#128279;&nbsp;
                        Ссылка на пост</a>
                </li>
                <li>
                    <a @click="report(post.id)"
                       class="block px-4 py-3 text-sm leading-tight text-gray-700 hover:bg-gray-100">&#128226;&nbsp;
                        Сообщить модератору</a>
                </li>
            </ul>
        </div>
    </div>
    <!-- Dropdown menu -->
    <div class="mt-2 text-sm/6 text-[#232323] text-justify break-words tracking-tight" v-html="post.comment">
    </div>
    <div class="mt-3">
        <span class="text-gray-500">#{{ post.id }}</span><a href="#"
                                                            @mousedown="$parent.comment+=(`${post.nick} (#${post.id}): `)"
                                                            class="ml-5 text-ska-blue">Ответить</a>
    </div>
</template>

<script>
export default {
    props: [
        'post'
    ],
    name: "PostComponent",
    data() {
        return {
            userInfo: null,
        }
    },
    methods: {
        showActionsDropdown(id) {
            let element = document.getElementById(`actions-dropdown-${id}`)
            if (element.classList.contains('block')) {
                element.classList.remove('block')
                element.classList.add('hidden')
                return
            }
            if (element.classList.contains('hidden')) {
                element.classList.remove('hidden')
                element.classList.add('block')
            }
        },
        showLink(id) {
            this.$parent.infoMessage = `Ссылка на выбранное сообщение: <a class="font-semibold underline" href="/gbook/post/${id}">https://skaenergy.ru/gbook/post/${id}</a>`
            document.getElementById(`actions-dropdown-${id}`).classList.remove('block')
            document.getElementById(`actions-dropdown-${id}`).classList.add('hidden')
            window.scrollTo(0, 0)
        },
        showUserModal(id) {
            let element = document.getElementById(`user-modal-${id}`)
            if (!this.post.user)
                return
            if (!this.userInfo) {
                this.axios.get(`/api/user/${this.post.user.id}`).then(res => {
                        this.userInfo = res.data.data;
                        element.classList.remove('hidden')
                        element.classList.add('block')
                    }
                )
            } else {
                element.classList.remove('hidden')
                element.classList.add('block')
            }
        },
        closeUserModal(id) {
            let element = document.getElementById(`user-modal-${id}`)
            element.classList.remove('block')
            element.classList.add('hidden')
        },
        report(id) {
            document.getElementById(`actions-dropdown-${id}`).classList.remove('block')
            document.getElementById(`actions-dropdown-${id}`).classList.add('hidden')
            this.$parent.tryToReport(id)
        }
    }
}
</script>

<style scoped>

</style>
