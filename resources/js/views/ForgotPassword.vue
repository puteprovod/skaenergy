<template>
    <div class="flex justify-center px-6 max-w-screen-xl py-8 mx-auto">
        <div class="bg-white rounded-2xl shadow md:mt-0 xl:p-0">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8 w-full md:w-[21rem]">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                    Сброс пароля
                </h1>
                <div class="text-sm text-justify tracking-tight text-gray-500">
                    Если вы забыли ваш пароль - не беда! Просто укажите свой email и мы вышлем вам ссылку, чтобы вы могли выбрать себе новый.
                    <br><br>Для этого введите адрес Е-mail и нажмите кнопку "Сбросить пароль".
                </div>
                <form class="space-y-4 md:space-y-4">
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Ваш
                            Email</label>
                        <input type="email" v-model="email" id="email"
                               class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                               placeholder="name@pochta.ru" required>
                        <label for="email" v-if="errorMessage.email"
                               class="block py-3 px-3 mt-2 text-red-800 rounded-lg bg-red-50 font-semibold text-sm">{{
                                errorMessage.email[0]
                            }}</label>
                    </div>
                    <div v-if="infoMessage" v-html="infoMessage" id="infoMessage" class="mt-5 px-7 py-4 shadow-sm text-sm text-green-800 md:rounded-xl bg-green-200 text-sm" role="alert">
                    </div>
                    <div class="mb-3">
                        <input type="submit" @click.prevent="tryToRecover" value="Сбросить пароль"
                               class="bg-ska-blue rounded-lg hover:bg-[#274D82] font-medium text-sm text-white p-2.5 w-full cursor-pointer">
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
    name: "Forgot_password",
    mounted() {
        if (localStorage.getItem('x_xsrf_token')) {
            this.$router.push({name: 'index'})
        }
        this.picNumber = Math.ceil(Math.random() * 18);

    },
    created() {
        document.title = this.$route.meta.title;
    },
    data() {
        return {
            errorMessage: '',
            infoMessage: '',
            email: '',
            picNumber: 0,
        }
    },
    methods: {
        tryToRecover() {
            if (!this.email) {
                this.errorMessage = {
                    email: {0: "Не указан email"}
                }
                console.log(this.errorMessage)
                return
            }
            this.errorMessage = ''
            this.axios.post('/forgot-password', {
                email: this.email,
            })
                .then(res => {
                        this.infoMessage = '<span class="font-medium">Ссылка для восстановления отправлена на указанный email</span>'
                }).catch(error => {
                this.errorMessage = error.response.data.errors
            })
        }
    }
}
</script>

<style scoped>

</style>
