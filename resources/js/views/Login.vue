<template>
    <div class="flex justify-center px-6 max-w-screen-xl py-8 mx-auto">
        <div class="bg-white rounded-2xl shadow md:mt-0 xl:p-0">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8 w-full lg:w-[21rem]">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                    Вход на сайт
                </h1>
                <form class="space-y-4 md:space-y-4">
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Ваш
                            Email</label>
                        <input type="email" v-model="email" id="email"
                               class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                               placeholder="name@pochta.ru" required>
                   </div>
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Пароль
                          </label>
                        <input type="password" v-model="password" id="password" placeholder="••••••••"
                               class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                               required>
                        <label for="email" v-if="errorMessage.email" class="block py-3 px-3 mt-2 text-red-800 rounded-lg bg-red-50 font-semibold text-sm">{{ errorMessage.email[0] }}</label>
                        <label for="password" v-if="errorMessage.password" class="block py-3 px-3 mt-2 text-red-800 rounded-lg bg-red-50 font-semibold text-sm">{{ errorMessage.password[0] }}</label>
                    </div>
                    <div class="mb-4 text-center text-sm content-center align-center justify-center">
                        <div class="w-48 mx-auto">
                            <router-link to="/forgot_password" class="text-ska-blue">Забыли пароль?</router-link>
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="submit" @click.prevent="tryToLogin" value="Войти"
                               class="bg-ska-blue rounded-lg hover:bg-[#274D82] font-medium text-sm text-white p-2.5 w-full cursor-pointer">
                    </div>
                    <div class="mb-3">
                        <router-link to="/registration"><input type="button" value="Создать аккаунт"
                                                        class="bg-gray-200 text-gray-500 font-medium rounded-lg text-sm p-2.5 hover:bg-[#EAEAEA] w-full cursor-pointer">
                        </router-link>
                    </div>
                    <div class="text-gray-500 text-xs my-5 text-center">
                        или продолжите с
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
                            Google
                        </button>
                        </form>
                    </div>
                </form>
            </div>
        </div>
        <div class="pl-16 hidden lg:block flex-grow-0">
            <img :src="`/img/rollpics/${picNumber}.jpg`" class="object-scale-down shadow rounded-2xl">
        </div>
    </div>
</template>

<script>
export default {
    name: "Registration",
    mounted() {
        if (localStorage.getItem('x_xsrf_token')) {
        this.$router.push({name:'index'})
        }
        this.picNumber =  Math.ceil(Math.random() * 18);

    },
    created() {
        document.title = this.$route.meta.title;
    },
    data() {
        return {
            errorMessage: '',
            email: '',
            password: '',
            picNumber: 0,
        }
    },
    methods: {
        tryToLogin() {
            if (!this.email || !this.password){
                this.errorMessage = {
                    password: {0:"Не все поля заполнены"}
            }
                return
            }
            this.errorMessage = '';
            axios.get('/sanctum/csrf-cookie').then(response => {
                this.axios.post('/login', {
                    email: this.email,
                    password: this.password,
                })
                    .then(res => {
                        localStorage.setItem('x_xsrf_token', res.config.headers['X-XSRF-TOKEN'])
                        this.$router.push({name: 'index'})
                    }).catch(error => {
                    console.log(error.response)
                    this.errorMessage = error.response.data.errors
                    console.log(this.errorMessage)
                }).finally(() => {
                    }
                )
            });
        }
    }
}
</script>

<style scoped>

</style>
