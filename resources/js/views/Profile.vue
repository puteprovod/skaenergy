<template>
    <div v-if="infoMessage" v-html="infoMessage" id="infoMessage" class="mt-5 px-7 font-semibold md:w-[50.5rem] max-w-screen-lg py-4 shadow-sm mx-auto text-sm text-ska-blue md:rounded-xl bg-blue-200 text-sm" role="alert">
    </div>
    <div class="flex flex-wrap justify-center py-5 mx-auto">
        <div class="bg-white rounded-2xl shadow basis-full md:basis-auto md:mt-0 xl:p-0 mb-5 md:mb-0 md:mr-10">

            <div class="p-6 space-y-4 md:space-y-6 w-full sm:p-8 md:w-[25rem] border-b">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                    Данные пользователя
                </h1>
                <form class="space-y-4 md:space-y-4">
                    <div>
                        <label for="nick" class="block mb-2 text-sm font-medium text-gray-900">Ник/Имя</label>
                        <input type="text" v-model="nick" id="nick"
                               class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                               placeholder="" required>
                        <label for="nick" v-if="errorMessage && errorMessage.nick"
                               class="block py-2 px-3 mt-2 text-red-800 rounded-lg bg-red-50 text-xs">{{
                                errorMessage.nick[0]
                            }}</label>
                    </div>
                    <div>
                        <label for="city" class="block mb-5 text-sm font-medium text-gray-900">Аватар <span
                            class="text-gray-400 text-xs">(необязательно)</span></label>
                        <div v-if="user && user.image_url" class="mb-5 my-3 flex">
                            <img :src="user.image_url" class="w-[10rem] h-[10rem] border rounded-full p-3" alt="">
                            <span @click.prevent="tryToDeleteImage" class="text-2xl font-bold text-red-800 cursor-pointer" title="Удалить">&times;</span>
                        </div>
                        <input
                            class="block p-2 w-full text-sm text-gray-900 border border-gray-200 cursor-pointer focus:outline-none"
                            id="file" @change="handleFileUpload( $event )" ref="file" type="file">
                        <p class="mt-1 text-xs text-gray-400" id="file_input_help">PNG, JPG, GIF, SVG, WEBP (Не более
                            1000x1000px).</p>
                        <label for="city" v-if="errorMessage && errorMessage.avatar"
                               class="block py-2 px-3 mt-2 text-red-800 rounded-lg bg-red-50 text-xs">{{
                                errorMessage.avatar[0]
                            }}</label>
                    </div>
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                        <input type="email" v-model="email" id="email"
                               class="bg-gray-200 border border-gray-300 text-gray-600 font-semibold sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                               placeholder="name@pochta.ru" disabled>
                        <label for="email" v-if="errorMessage && errorMessage.email"
                               class="block py-2 px-3 mt-2 text-red-800 rounded-lg bg-red-50 text-xs">{{
                                errorMessage.email[0]
                            }}</label>
                    </div>
                    <div>
                        <label for="city" class="block mb-2 text-sm font-medium text-gray-900">Город <span
                            class="text-gray-400 text-xs">(необязательно)</span></label>
                        <input type="text" v-model="city" id="city"
                               class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                               placeholder="">
                        <label for="city" v-if="errorMessage && errorMessage.city"
                               class="block py-2 px-3 mt-2 text-red-800 rounded-lg bg-red-50 text-xs">{{
                                errorMessage.city[0]
                            }}</label>
                    </div>
                    <div>
                        <label for="country" class="block mb-2 text-sm font-medium text-gray-900">Страна <span
                            class="text-gray-400 text-xs">(необязательно)</span></label>
                        <input type="text" v-model="country" id="country"
                               class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                               placeholder="">
                        <label for="city" v-if="errorMessage && errorMessage.country"
                               class="block py-2 px-3 mt-2 text-red-800 rounded-lg bg-red-50 text-xs">{{
                                errorMessage.country[0]
                            }}</label>
                    </div>
                    <div>
                        <label for="favourite_clubs" class="block mb-2 text-sm font-medium text-gray-900">Любимые клубы
                            <span
                                class="text-gray-400 text-xs">(необязательно)</span></label>
                        <input type="text" v-model="favourite_clubs" id="favourite_clubs"
                               class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                               placeholder="">
                        <label for="city" v-if="errorMessage && errorMessage.favourite_clubs"
                               class="block py-2 px-3 mt-2 text-red-800 rounded-lg bg-red-50 text-xs">{{
                                errorMessage.favourite_clubs[0]
                            }}</label>
                    </div>
                    <div>
                        <input @click.prevent="tryToUpdateProfile" type="button" value="Сохранить"
                               class="mt-2 bg-ska-blue text-mild-white font-medium rounded-lg text-sm p-2.5 hover:bg-[#274D82] w-full cursor-pointer">
                    </div>
                </form>
            </div>
        </div>
        <div class="flex flex-col space-y-6 basis-full md:basis-auto">
            <div class="bg-white rounded-2xl shadow md:mt-0 xl:p-0 h-full">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8 w-full md:w-[23rem]">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                        Изменение пароля
                    </h1>
                    <form class="space-y-4 md:space-y-4">
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 mt-6">Текущий
                                пароль
                            </label>
                            <input type="password" v-model="current_password" id="current_password" placeholder=""
                                   class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                   required>
                            <label for="password" v-if="errorMessage && errorMessage.current_password"
                                   class="block py-2 px-3 mt-2 text-red-800 rounded-lg bg-red-50 text-xs">{{
                                    errorMessage.current_password[0]
                                }}</label>
                        </div>
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Новый пароль
                                <span
                                    class="text-gray-500 text-xs">(не менее 6 символов)</span></label>
                            <input type="password" v-model="password" id="password" placeholder=""
                                   class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                   required>
                            <label for="password" v-if="errorMessage && errorMessage.password"
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
                            <label for="password_confirmation" v-if="errorMessage && errorMessage.password_confirmation"
                                   class="block py-2 px-3 mt-2 text-red-800 rounded-lg bg-red-50 text-xs">{{
                                    errorMessage.password_confirmation[0]
                                }}</label>
                        </div>
                        <div>
                            <input type="button" @click.prevent="tryToChangePassword" value="Сохранить"
                                   class="mt-2 bg-ska-blue text-mild-white font-medium rounded-lg text-sm p-2.5 hover:bg-[#274D82] w-full cursor-pointer">
                        </div>
                    </form>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow md:mt-0 xl:p-0 h-full">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8 w-full md:w-[23rem]">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                        Удаление аккаунта
                    </h1>
                    <form class="space-y-4 md:space-y-4">
                        <div class="flex items-start">
                            <div class="text-sm tracking-tight">
                                <div class="font-light text-gray-500 text-justify">
                                    Если вы выберете удаление аккаунта, ваши данные и ваша учетная запись будет навсегда
                                    удалена с сайта. Удаленная учетная запись восстановлению не подлежит.
                                </div>
                            </div>
                        </div>
                        <div>
                            <input @click.prevent="showConfirmation" type="button" value="Удалить аккаунт"
                                   class="mt-2 bg-red-600 text-mild-white font-medium rounded-lg text-sm p-2.5 hover:bg-red-400 w-full cursor-pointer">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- The Modal -->
    <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="w-full">
                <span @click.prevent="closeConfirmation" class="close">&times;</span>
                <div class="p-10">
                Введите ваш текущий пароль для подтверждения удаления аккаунта:
                <br>
                    <div class="mt-5">
                <input type="password" v-model="current_password2" id="current_password2" placeholder=""
                       class="bg-gray-50 w-64 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                       required>
                        <label for="city" v-if="errorMessageModal"
                               class="block py-2 px-3 mt-2 text-red-800 rounded-lg bg-red-50 text-xs w-64">{{
                                errorMessageModal
                            }}</label>
                        <input @click.prevent="tryToDeleteAccount" type="button" value="Удалить аккаунт"
                               class="mt-2 bg-red-600 text-mild-white font-medium rounded-lg text-sm p-2.5 px-6 mt-2 hover:bg-red-400 cursor-pointer">

                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import Rules from "./components/Rules.vue";
import Personal from "./components/Personal.vue";

export default {
    name: "Registration",
    mounted() {
        this.picNumber = Math.ceil(Math.random() * 18);
        this.picNumber2 = Math.ceil(Math.random() * 18);
        while (this.picNumber2 === this.picNumber) {
            this.picNumber2 = Math.ceil(Math.random() * 18);
        }
        this.getUser()
    },
    created() {
        document.title = this.$route.meta.title;
    },
    data() {
        return {
            errorMessage: '',
            errorMessageModal: '',
            nick: '',
            email: '',
            avatar: '',
            current_password: '',
            current_password2: '',
            password: '',
            city: '',
            password_confirmation: '',
            picNumber: 0,
            picNumber2: 0,
            user: null,
            favourite_clubs: '',
            country: '',
            infoMessage: ''
        }
    },
    methods: {
        getUser() {
            if (localStorage.getItem('x_xsrf_token')) {
                this.axios.get('/api/user').then(res => {
                        this.user = res.data.data;
                        this.nick = this.user.nick
                        this.city = this.user.city
                        this.email = this.user.email
                        this.country = this.user.country
                        this.favourite_clubs = this.user.favourite_clubs
                        this.$parent.$parent.userLogged(this.user)
                    }
                ).catch(
                    err => {
                        localStorage.removeItem('x_xsrf_token')
                        window.location.href = '/';
                    }
                )
            } else {
                this.$router.push({name: 'index'})
            }
        },
        tryToChangePassword() {
            this.errorMessage = ''
            this.infoMessage = ''
                this.axios.put('/password', {
                    current_password: this.current_password,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                })
                    .then(res => {
                        this.current_password = ''
                        this.password=''
                        this.password_confirmation=''
                        this.infoMessage = 'Ваш пароль успешно изменен'
                        window.scrollTo(0, 0)
                    }).catch(error => {
                    this.errorMessage = error.response.data.errors
                }).finally(() => {
                    }
                )
        },
        tryToUpdateProfile() {
            let formData = new FormData();
            formData.append('avatar', this.avatar);
            formData.append('nick', this.nick);
            formData.append('city', this.city);
            formData.append('country', this.country);
            formData.append('favourite_clubs', this.favourite_clubs);
            formData.append('_method', 'patch');
            this.errorMessage = ''
            this.infoMessage = ''
            this.axios.post('/profile',
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(res => {
                    this.getUser()
                    this.infoMessage = 'Данные пользователя успешно изменены'
                    window.scrollTo(0, 0)
                }).catch(error => {
                this.errorMessage = error.response.data.errors
                console.log(this.errorMessage)
            }).finally(() => {
                }
            )
        },
        tryToDeleteImage() {''
            this.errorMessage = ''
            this.infoMessage = ''
            this.axios.delete('/profile/delete_image')
                .then(res => {
                    this.getUser()
                    this.infoMessage = 'Аватар успешно удален'
                    window.scrollTo(0, 0)
                }).catch(error => {
                this.errorMessage = error.response.data.errors
                console.log(this.errorMessage)
            }).finally(() => {
                }
            )
        },
        handleFileUpload( event ){
            this.avatar = event.target.files[0];
        },
        showConfirmation() {
            document.getElementById("myModal").style.display = "block";
        },
        closeConfirmation() {
            document.getElementById("myModal").style.display = "none";
            this.$parent.rulesOpened = false
        },
        tryToDeleteAccount() {
            this.errorMessage = ''
            this.infoMessage = ''
            console.log(this.current_password2)
            this.axios.post('/profile',{
                password: this.current_password2,
                _method: 'delete'
            })
                .then(res => {
                    this.$router.push({name: 'index',query: { infoMessageX: 'Пользователь успешно удален' }})
                }).catch(error => {
                this.errorMessageModal = error.response.data.message
                console.log(error)
            })
        }
    }
}
</script>

<style scoped>

</style>
