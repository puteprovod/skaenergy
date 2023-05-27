<template>
    <div class="flex justify-center px-6 max-w-screen-xl py-8 mx-auto">
        <div class="bg-white rounded-2xl shadow md:mt-0 xl:p-0">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8 w-full lg:w-[21rem]">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                    Регистрация на сайте
                </h1>
                <form class="space-y-4 md:space-y-4">
                    <div>
                        <label for="nick" class="block mb-2 text-sm font-medium text-gray-900">Ваш Ник/Имя</label>
                        <input type="text" v-model="nick" id="nick"
                               class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                               placeholder="" required>
                        <label for="nick" v-if="errorMessage.nick"
                               class="block py-2 px-3 mt-2 text-red-800 rounded-lg bg-red-50 text-xs">{{
                                errorMessage.nick[0]
                            }}</label>
                    </div>
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Ваш
                            email</label>
                        <input type="email" v-model="email" id="email"
                               class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                               placeholder="name@pochta.ru" required>
                        <label for="email" v-if="errorMessage.email"
                               class="block py-2 px-3 mt-2 text-red-800 rounded-lg bg-red-50 text-xs">{{
                                errorMessage.email[0]
                            }}</label>
                    </div>
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Пароль
                            <span
                                class="text-gray-500 text-xs">(не менее 6 символов)</span></label>
                        <input type="password" v-model="password" id="password" placeholder=""
                               class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                               required>
                        <label for="password" v-if="errorMessage.password"
                               class="block py-2 px-3 mt-2 text-red-800 rounded-lg bg-red-50 text-xs">{{
                                errorMessage.password[0]
                            }}</label>
                    </div>
                    <div>
                        <label for="password_confirmation"
                               class="block mb-2 text-sm font-medium text-gray-900">Подтверждение
                            пароля</label>
                        <input type="password" v-model="password_confirmation" id="password_confirmation"
                               placeholder=""
                               class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                               required="">
                        <label for="password_confirmation" v-if="errorMessage.password_confirmation"
                               class="block py-2 px-3 mt-2 text-red-800 rounded-lg bg-red-50 text-xs">{{
                                errorMessage.password_confirmation[0]
                            }}</label>
                    </div>
                    <div>
                        <label for="city" class="block mb-2 text-sm font-medium text-gray-900">Город <span
                            class="text-gray-500 text-xs">(необязательно)</span></label>
                        <input type="text" v-model="city" id="city"
                               class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                               placeholder="">
                        <label for="city" v-if="errorMessage.city"
                               class="block py-2 px-3 mt-2 text-red-800 rounded-lg bg-red-50 text-xs">{{
                                errorMessage.city[0]
                            }}</label>
                    </div>
                    <div class="flex items-start">
                        <div class="text-sm tracking-tight">
                            <div class="font-light text-gray-500 text-justify">Создавая аккаунт, я
                                соглашаюсь c <a @click.prevent="rulesOpened = true"
                                                class="font-medium text-primary-600 hover:underline" href="#">Правилами
                                    сайта</a> и даю согласие на <a href="#" @click.prevent="personalOpened = true"
                                                                   class="font-medium text-primary-600 hover:underline">обработку
                                    персональных данных</a>.
                            </div>
                        </div>
                    </div>
                    <input @click.prevent="tryToRegister" type="submit" value="Создать аккаунт"
                           class="bg-gradient-to-r from-red-500 to-ska-blue hover:bg-gradient-to-bl rounded-lg hover:bg-[#274D82] font-medium text-sm text-white p-2.5 w-full cursor-pointer">
                    <div class="mb-3">
                        <router-link to="/login"><input type="button" value="У меня уже есть аккаунт"
                                                        class="bg-gray-200 text-gray-500 font-medium rounded-lg text-sm p-2.5 hover:bg-[#EAEAEA] w-full cursor-pointer">
                        </router-link>
                    </div>
                </form>
                <div class="text-gray-500 text-xs my-5 text-center">
                    или продолжите с
                </div>
                <div>
                    <form action="/google_auth/redirect" method="get">
                        <button type="submit"
                                class="text-white bg-[#d95040] w-full justify-center hover:bg-[#e27468]/90 focus:ring-4 focus:outline-none focus:ring-[#e27468]/50 rounded-lg text-sm p-2.5 inline-flex mb-2">
                            <svg class="w-4 h-4 mr-2 -ml-1" aria-hidden="true" focusable="false" data-prefix="fab"
                                 data-icon="google" role="img" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="-110 -110 558 612">
                                <path fill="currentColor"
                                      d="M488 261.8C488 403.3 391.1 504 248 504 110.8 504 0 393.2 0 256S110.8 8 248 8c66.8 0 123 24.5 166.3 64.9l-67.5 64.9C258.5 52.6 94.3 116.6 94.3 256c0 86.5 69.1 156.6 153.7 156.6 98.2 0 135-70.4 140.8-106.9H248v-85.3h236.1c2.3 12.7 3.9 24.9 3.9 41.4z"></path>
                            </svg>
                            Google
                        </button>
                    </form>
                    <form action="/vk_auth/redirect" method="get">
                        <button type="submit"
                                class="text-white bg-[#5b7aa8] w-full justify-center hover:bg-[#7596bc]/90 focus:ring-4 focus:outline-none focus:ring-[#7596bc]/50 rounded-lg text-sm p-2.5 inline-flex mb-2">
                            <svg class="w-4 h-4 mr-2 -ml-1" aria-hidden="true" focusable="false" data-prefix="fab"
                                 data-icon="google" role="img" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="190 190 630 540">
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
                            VK
                        </button>
                    </form>
                    <form action="/apple_auth/redirect" method="get">
                        <button type="submit"
                                class="text-white bg-[#050708] w-full justify-center hover:bg-gray-900/90 focus:ring-4 focus:outline-none focus:ring-gray-900/50 rounded-lg text-sm p-2.5 inline-flex mb-2">
                            <svg class="w-4 h-4 mr-2 -ml-1" aria-hidden="true" focusable="false" data-prefix="fab"
                                 data-icon="apple" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 00 384 472">
                                <path fill="currentColor"
                                      d="M318.7 268.7c-.2-36.7 16.4-64.4 50-84.8-18.8-26.9-47.2-41.7-84.7-44.6-35.5-2.8-74.3 20.7-88.5 20.7-15 0-49.4-19.7-76.4-19.7C63.3 141.2 4 184.8 4 273.5q0 39.3 14.4 81.2c12.8 36.7 59 126.7 107.2 125.2 25.2-.6 43-17.9 75.8-17.9 31.8 0 48.3 17.9 76.4 17.9 48.6-.7 90.4-82.5 102.6-119.3-65.2-30.7-61.7-90-61.7-91.9zm-56.6-164.2c27.3-32.4 24.8-61.9 24-72.5-24.1 1.4-52 16.4-67.9 34.9-17.5 19.8-27.8 44.3-25.6 71.9 26.1 2 49.9-11.4 69.5-34.3z"></path>
                            </svg>
                            Apple
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="pl-12 hidden lg:block flex-grow-0">
            <img :src="`/img/rollpics/${picNumber}.jpg`" class="object-scale-down shadow rounded-2xl">
        </div>
    </div>
    <Rules :trigger="rulesOpened" :key="rulesOpened"></Rules>
    <Personal :trigger="personalOpened" :key="personalOpened"></Personal>
</template>

<script>
import Rules from "./components/Rules.vue";
import Personal from "./components/Personal.vue";

export default {
    name: "Registration",
    components: {Personal, Rules},
    mounted() {
        this.picNumber = Math.ceil(Math.random() * 18);
    },
    created() {
        document.title = this.$route.meta.title;
    },
    data() {
        return {
            errorMessage: '',
            nick: '',
            email: '',
            password: '',
            city: '',
            password_confirmation: '',
            picNumber: 0,
            rulesOpened: false,
            personalOpened: false
        }
    },
    methods: {
        tryToRegister() {
            axios.get('/sanctum/csrf-cookie').then(response => {
                this.axios.post('/register', {
                    nick: this.nick,
                    email: this.email,
                    password: this.password,
                    city: this.city,
                    password_confirmation: this.password_confirmation,
                })
                    .then(res => {
                        localStorage.setItem('x_xsrf_token', res.config.headers['X-XSRF-TOKEN'])
                        this.$router.push({
                            name: 'index',
                            query: {infoMessageX: "Вы успешно зарегистрировались на сайте. Чтобы написать первый пост вам необходимо подтвердить email."}
                        })
                    }).catch(error => {
                    console.log(error.response.data.errors)
                    this.errorMessage = error.response.data.errors
                    console.log(this.errorMessage)
                }).finally(() => {
                    }
                )
            });
        },
    }
}
</script>

<style scoped>

</style>
