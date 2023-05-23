<template>
    <div v-if="user" class="text-sm bg-white mt-5 shadow-sm font-medium text-gray-500 md:rounded-xl">
        <div class="p-7 pb-6 border-b">
            <div v-if="user.image_url" class="inline-block align-middle mr-4">
                <img :src="user.image_url" class="w-20 h-20 p-1 rounded-full border" alt="avatar">
            </div>
            <div v-else
                 class="rounded-full text-ska-blue border text-center h-20 w-20 align-middle inline-block mr-3 align-center my-auto">
                <div class="text-3xl align-center content-center py-5">{{
                        user.nick.substring(0, 1).toUpperCase()
                    }}
                </div>
            </div>
            <div class="inline-block text-base align-middle mt-2.5">
                <span class="text-ska-blue font-bold break-all">{{ user.nick }}</span><br>
                <span class="text-xs tracking-tighter text-gray-500 break-all">{{ user.email }}</span><br>
                <span v-if="user.city" class="text-xs text-gray-500 tracking-tight break-all">{{ user.city }}</span>
            </div>
        </div>
        <div class="p-7 pt-6">
            <div class="mb-4" v-if="showAllPosts">
                <a class="hover:text-ska-blue" href="#" @click.prevent="$parent.getUserPosts(this.user)"><span>Всего постов</span><span
                    class="ml-2">{{ user.post_count }}</span></a>
            </div>
            <div class="mb-4">
                <router-link :to="{name: 'profile'}" class="hover:text-ska-blue">Изменить профиль</router-link>
            </div>
            <div>
                <a class="hover:text-ska-blue" @click.prevent="tryToLogout" href="#">Выйти</a>
            </div>
        </div>
    </div>
    <div v-if="user===null" class="text-sm bg-white text-sm mt-5 p-7 text-center shadow-sm md:rounded-xl">
        <div class="text-lg font-semibold mb-3">Войти</div>
        <div class="mb-3">
            <input @mousedown="errorMessage = null" v-on:keyup.enter="tryToLogin" type="text" name="email"
                   v-model="email" placeholder="E-mail"
                   class="w-full border rounded-lg focus:border-ska-blue border-gray-300 text-sm">
        </div>
        <div class="mb-4">
            <input @mousedown="errorMessage = null" v-on:keyup.enter="tryToLogin" type="password" name="password"
                   v-model="password" placeholder="Пароль"
                   class="w-full border rounded-lg focus:border-ska-blue border-gray-300 text-sm">
        </div>
        <div v-if="errorMessage" class="mb-4">
            <div class="py-4 px-2 mb-3 text-sm text-red-800 rounded-lg bg-red-50 text-sm" role="alert">
                <span class="font-medium">{{ errorMessage }}</span>
            </div>
        </div>
        <div class="mb-4 text-center content-center align-center justify-center">
            <div class="w-48 mx-auto">
                <router-link :to="{name: 'forgot_password'}" class="text-ska-blue">Забыли пароль?</router-link>
            </div>
        </div>
        <div class="mb-3">
            <input type="submit" @click.prevent="tryToLogin" value="Войти"
                   class="bg-ska-blue rounded-lg hover:bg-[#274D82] font-medium text-sm text-white p-2.5 w-full cursor-pointer">
        </div>
        <div class="mb-3">
            <router-link :to="{name: 'registration'}"><input type="button" value="Создать аккаунт"
                                                             class="bg-gray-200 text-gray-500 font-medium rounded-lg text-sm p-2.5 hover:bg-[#EAEAEA] w-full cursor-pointer">
            </router-link>
        </div>
        <div class="text-gray-500 text-xs my-5">
            или войти с помощью
        </div>
        <div>
            <form action="/google_auth/redirect" method="get">
                <button type="submit"
                        class="text-white bg-[#d95040] w-full justify-center hover:bg-[#e27468]/90 focus:ring-4 focus:outline-none focus:ring-[#e27468]/50 rounded-lg text-sm p-2.5 inline-flex dark:focus:ring-[#e27468]/55 mb-2">
                    <svg class="w-4 h-4 mr-2 -ml-1" aria-hidden="true" focusable="false" data-prefix="fab"
                         data-icon="google" role="img" xmlns="http://www.w3.org/2000/svg"
                         viewBox="-110 -110 558 612">
                        <path fill="currentColor"
                              d="M488 261.8C488 403.3 391.1 504 248 504 110.8 504 0 393.2 0 256S110.8 8 248 8c66.8 0 123 24.5 166.3 64.9l-67.5 64.9C258.5 52.6 94.3 116.6 94.3 256c0 86.5 69.1 156.6 153.7 156.6 98.2 0 135-70.4 140.8-106.9H248v-85.3h236.1c2.3 12.7 3.9 24.9 3.9 41.4z"></path>
                    </svg>
                    Войти с Google
                </button>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    name: "LoginForm",
    data() {
        return {
            email: '',
            password: '',
            user: '',
            errorMessage: null
        }
    },
    props: [
        'showAllPosts'
    ],
    mounted() {
        this.getUser()
    },
    methods: {
        getUser() {
            if (localStorage.getItem('x_xsrf_token') || this.$route.query.social === '1') {
                this.axios.get('/api/user').then(res => {
                        this.user = res.data.data;
                        this.$parent.userLogged(this.user)
                        this.$parent.$parent.$parent.userLogged(this.user)
                        if (this.$route.query.social === '1') {
                            this.$parent.successLogin()
                            localStorage.setItem('x_xsrf_token', 'social')
                            this.password = ''
                        }
                    }
                ).catch(
                    err => {
                        localStorage.removeItem('x_xsrf_token')
                        this.user = null
                        window.location.href = '/';
                    }
                )
            } else {
                this.user = null
            }
        },
        tryToLogin() {
            this.errorMessage = ''
            if (!this.email || !this.password) {
                this.errorMessage = 'Не все поля заполнены'
                return
            }

            axios.get('/sanctum/csrf-cookie').then(response => {
                this.axios.post('/login', {
                    'email': this.email,
                    'password': this.password
                })
                    .then(res => {
                        this.$parent.successLogin()
                        localStorage.setItem('x_xsrf_token', res.config.headers['X-XSRF-TOKEN'])
                        this.password = ''
                        this.getUser()
                    }).catch(error => {
                    this.errorMessage = error.response.data.message
                }).finally(() => {
                    }
                )
            });

        },
        tryToLogout() {
            axios.post('/logout')
                .then(res => {
                    localStorage.removeItem('x_xsrf_token')
                    this.user = null
                    this.$parent.userLoggedOut()
                })
        }

    }
}
</script>

<style scoped>

</style>
