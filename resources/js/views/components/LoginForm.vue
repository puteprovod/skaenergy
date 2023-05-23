<template>
    <div v-if="user" class="text-sm bg-white mt-5 shadow-sm font-medium text-gray-500 md:rounded-xl">
        <div class="p-7 pb-6 border-b">
            <div v-if="user.image_url" class="inline-block align-middle mr-4">
                <img :src="user.image_url" class="w-20 h-20 p-1 rounded-full border" alt="avatar">
            </div>
            <div v-else
                 class="rounded-full text-ska-blue border text-center h-20 w-20 align-middle inline-block mr-3 align-center my-auto">
                <div class="text-3xl align-center content-center py-5">{{ user.nick.substring(0, 1).toUpperCase() }}</div>
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
            <input @mousedown="errorMessage = null" v-on:keyup.enter="tryToLogin" type="text" name="email" v-model="email" placeholder="E-mail"
                   class="w-full border rounded-lg focus:border-ska-blue border-gray-300 text-sm">
        </div>
        <div class="mb-4">
            <input @mousedown="errorMessage = null" v-on:keyup.enter="tryToLogin" type="password" name="password" v-model="password" placeholder="Пароль"
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
            <button type="button"
                    class="text-white bg-[#d95040] w-full justify-center hover:bg-[#e27468]/90 focus:ring-4 focus:outline-none focus:ring-[#e27468]/50 rounded-lg text-sm p-2.5 inline-flex dark:focus:ring-[#e27468]/55 mb-2">
                <svg class="w-4 h-4 mr-2 -ml-1" aria-hidden="true" focusable="false" data-prefix="fab"
                     data-icon="google" role="img" xmlns="http://www.w3.org/2000/svg"
                     viewBox="-110 -110 558 612">
                    <path fill="currentColor"
                          d="M488 261.8C488 403.3 391.1 504 248 504 110.8 504 0 393.2 0 256S110.8 8 248 8c66.8 0 123 24.5 166.3 64.9l-67.5 64.9C258.5 52.6 94.3 116.6 94.3 256c0 86.5 69.1 156.6 153.7 156.6 98.2 0 135-70.4 140.8-106.9H248v-85.3h236.1c2.3 12.7 3.9 24.9 3.9 41.4z"></path>
                </svg>
                Войти с Google
            </button>
            <button type="button"
                    class="text-white bg-[#5b7aa8] w-full justify-center hover:bg-[#7596bc]/90 focus:ring-4 focus:outline-none focus:ring-[#7596bc]/50 rounded-lg text-sm p-2.5 inline-flex dark:focus:ring-[#7596bc]/55 mb-2">
                <svg class="w-4 h-4 mr-2 -ml-1" aria-hidden="true" focusable="false" data-prefix="fab"
                     data-icon="google" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="190 190 630 540">
                    <defs id="defs2446"/>
                    <g id="layer1">
                        <g id="g3257">
                            <path
                                d="M 503.94561,704.02937 L 543.21464,704.02937 C 543.21464,704.02937 555.07355,702.72226 561.13718,696.19824 C 566.71036,690.20169 566.53222,678.94892 566.53222,678.94892 C 566.53222,678.94892 565.76414,626.25707 590.21533,618.49765 C 614.32875,610.84928 645.28544,669.422 678.09524,691.94606 C 702.90734,708.98485 721.76221,705.25552 721.76221,705.25552 L 809.501,704.02937 C 809.501,704.02937 855.39584,701.19767 833.63292,665.11199 C 831.85154,662.16461 820.95504,638.41904 768.39506,589.63234 C 713.37584,538.56917 720.75122,546.83061 787.0209,458.5042 C 827.37958,404.71345 843.51149,371.87589 838.47041,357.81224 C 833.66762,344.41255 803.98103,347.95218 803.98103,347.95218 L 705.19537,348.56294 C 705.19537,348.56294 697.86626,347.56583 692.43883,350.81395 C 687.12938,353.99036 683.72163,361.41201 683.72163,361.41201 C 683.72163,361.41201 668.08018,403.03386 647.23571,438.43707 C 603.24717,513.13013 585.65546,517.08387 578.46516,512.4384 C 561.73637,501.6275 565.91683,469.01666 565.91683,445.84252 C 565.91683,373.45368 576.89662,343.272 544.53564,335.45938 C 533.79877,332.86828 525.88898,331.15399 498.42564,330.87406 C 463.17512,330.51547 433.3474,330.98279 416.45436,339.25811 C 405.21547,344.76189 396.54454,357.02334 401.82853,357.72895 C 408.35949,358.59882 423.14264,361.71971 430.98071,372.38486 C 441.10682,386.16164 440.75286,417.08826 440.75286,417.08826 C 440.75286,417.08826 446.57126,502.30072 427.16809,512.88259 C 413.85401,520.14229 395.58676,505.32213 356.36862,437.55563 C 336.27835,402.84415 321.10422,364.47044 321.10422,364.47044 C 321.10422,364.47044 318.18229,357.30096 312.96308,353.46289 C 306.6334,348.81279 297.78896,347.33911 297.78896,347.33911 L 203.91254,347.95218 C 203.91254,347.95218 189.8234,348.34547 184.64583,354.47388 C 180.03969,359.92907 184.27802,371.19804 184.27802,371.19804 C 184.27802,371.19804 257.76808,543.14061 340.9886,629.78975 C 417.30341,709.24396 503.94561,704.02937 503.94561,704.02937"
                                id="path2442"
                                style="fill:#ffffff;fill-opacity:1;fill-rule:evenodd;stroke:none"/>
                        </g>
                    </g>
                </svg>
                Войти с VK
            </button>
            <button type="button"
                    class="text-white bg-[#050708] w-full justify-center hover:bg-gray-900/90 focus:ring-4 focus:outline-none focus:ring-gray-900/50 rounded-lg text-sm p-2.5 inline-flex dark:focus:ring-gray-900/55 mb-2">
                <svg class="w-4 h-4 mr-2 -ml-1" aria-hidden="true" focusable="false" data-prefix="fab"
                     data-icon="apple" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 00 384 472">
                    <path fill="currentColor"
                          d="M318.7 268.7c-.2-36.7 16.4-64.4 50-84.8-18.8-26.9-47.2-41.7-84.7-44.6-35.5-2.8-74.3 20.7-88.5 20.7-15 0-49.4-19.7-76.4-19.7C63.3 141.2 4 184.8 4 273.5q0 39.3 14.4 81.2c12.8 36.7 59 126.7 107.2 125.2 25.2-.6 43-17.9 75.8-17.9 31.8 0 48.3 17.9 76.4 17.9 48.6-.7 90.4-82.5 102.6-119.3-65.2-30.7-61.7-90-61.7-91.9zm-56.6-164.2c27.3-32.4 24.8-61.9 24-72.5-24.1 1.4-52 16.4-67.9 34.9-17.5 19.8-27.8 44.3-25.6 71.9 26.1 2 49.9-11.4 69.5-34.3z"></path>
                </svg>
                Войти с Apple
            </button>
            <button type="button"
                    class="text-white bg-[#1da1f2] w-full justify-center hover:bg-[#1da1f2]/90 focus:ring-4 focus:outline-none focus:ring-[#1da1f2]/50 rounded-lg text-sm p-2.5 inline-flex dark:focus:ring-[#1da1f2]/55 mb-2">
                <svg class="w-4 h-4 mr-2 -ml-1" aria-hidden="true" focusable="false" data-prefix="fab"
                     data-icon="twitter" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 -50 512 512">
                    <path fill="currentColor"
                          d="M459.4 151.7c.325 4.548 .325 9.097 .325 13.65 0 138.7-105.6 298.6-298.6 298.6-59.45 0-114.7-17.22-161.1-47.11 8.447 .974 16.57 1.299 25.34 1.299 49.06 0 94.21-16.57 130.3-44.83-46.13-.975-84.79-31.19-98.11-72.77 6.498 .974 12.99 1.624 19.82 1.624 9.421 0 18.84-1.3 27.61-3.573-48.08-9.747-84.14-51.98-84.14-102.1v-1.299c13.97 7.797 30.21 12.67 47.43 13.32-28.26-18.84-46.78-51.01-46.78-87.39 0-19.49 5.197-37.36 14.29-52.95 51.65 63.67 129.3 105.3 216.4 109.8-1.624-7.797-2.599-15.92-2.599-24.04 0-57.83 46.78-104.9 104.9-104.9 30.21 0 57.5 12.67 76.67 33.14 23.72-4.548 46.46-13.32 66.6-25.34-7.798 24.37-24.37 44.83-46.13 57.83 21.12-2.273 41.58-8.122 60.43-16.24-14.29 20.79-32.16 39.31-52.63 54.25z"></path>
                </svg>
                Войти с Twitter
            </button>
            <button type="button"
                    class="text-white bg-[#2e6ab7] w-full justify-center hover:bg-[#2e6ab7]/90 focus:ring-4 focus:outline-none focus:ring-[#2e6ab7]/50 rounded-lg text-sm p-2.5 inline-flex dark:focus:ring-[#2e6ab7]/55 mb-2">
                <svg class="w-4 h-4 mr-2 -ml-1" aria-hidden="true" focusable="false" data-prefix="fab"
                     data-icon="facebook-f" role="img" xmlns="http://www.w3.org/2000/svg"
                     viewBox="-60 -80 320 592">
                    <path fill="currentColor"
                          d="M279.1 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.4 0 225.4 0c-73.22 0-121.1 44.38-121.1 124.7v70.62H22.89V288h81.39v224h100.2V288z"></path>
                </svg>
                Войти с Facebook
            </button>
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
                        if (this.$route.query.social === '1'){
                            this.$parent.successLogin()
                            localStorage.setItem('x_xsrf_token', 'social')
                            this.password = ''
                            this.getUser()
                        }
                    }
                ).catch(
                    err => {
                        localStorage.removeItem('x_xsrf_token')
                        this.user = null
                        window.location.href = '/';
                    }
                )
            }
            else
            {
                this.user = null
            }
        },
        tryToLogin() {
            this.errorMessage = ''
            if (!this.email || !this.password){
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
