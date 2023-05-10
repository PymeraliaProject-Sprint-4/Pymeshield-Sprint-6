<template>
    <div class="container">
        <div class="main-body overflow-x-auto">
            <div class="row gutters-sm">
                <div class="col-md-4 mb-3 left-table">
                    <div class="card">
                        <div class="card-body">

                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="mt-5 font-bold" style="text-align: center;">{{ $t('personal-info') }}</h3>
                    <div class="text-center">
                        <div style="display: flex; justify-content: center; align-items: center;">
                            <img v-if="user.profile_image" :src="`/img/profile_images/${user.profile_image}`"
                                alt="imagen_del_usuario" class="mb-3 rounded-full w-48 h-48" id="block" />

                            <img v-else src="/img/default_profile.png" alt="imagen_del_usuario"
                                class="mb-3 rounded-full w-48 h-48" id="block" />
                        </div>
                        <button @click="openModal()"
                            class="ml-10 bg-orange-400 hover:bg-orange-600 font-medium py-1 px-2 mr-4 rounded-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110"><i
                                class="fas fa-images mr-2"></i>
                            {{ $t('image-changes') }}
                        </button>
                    </div>

                    <h4 id="block1" class="text-2xl font-bold"></h4>


                    <table class="table mt-10">
                        <thead>
                            <tr class="bg-orange-400 text-white">
                                <th class="px-6 py-3 uppercase">{{ $t('name') }}</th>
                                <th class="px-6 py-3 uppercase">{{ $t('lastname') }}</th>
                                <th class="px-6 py-3 uppercase">{{ $t('username') }}</th>
                                <th class="px-6 py-3 uppercase">{{ $t('email') }}</th>
                                <th class="px-6 py-3 uppercase">{{ $t('phone') }}</th>
                                <th class="px-6 py-3 uppercase">{{ $t('company') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="bg-orange-50 text-center">
                                    <input v-model="user.name" class="form-input" type="text" name="name" />
                                </td>
                                <td class="bg-orange-50 text-center">
                                    <input v-model="user.last_name" class="form-input" type="text" />
                                </td>
                                <td class="bg-orange-50 text-center">
                                    <input v-model="user.nick_name" class="form-input" type="text" />
                                </td>
                                <td class="bg-orange-50 text-center">
                                    <input v-model="user.email" class="form-input" type="text" />
                                </td>
                                <td class="bg-orange-50 text-center">
                                    <input v-model="user.phone" class="form-input" type="text" />
                                </td>
                                <td class="bg-orange-50 text-center">
                                    <select v-model="user.company_id" class="form-input">
                                        <option v-for="company in companies" :value="company.id"
                                            :selected="company.id === user.company_id">{{ company.name }}</option>
                                    </select>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <div class="flex justify-left mt-5">
                    <button @click="updateUserInfo"
                        class="bg-orange-400 hover:bg-orange-600 font-medium py-1 px-2 mr-4 rounded-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110 flex items-center"><i
                            class="far fa-save mr-2"></i>{{
                                $t('save-changes') }}</button>
                    <button @click="openModalPassword()"
                        class="bg-orange-400 hover:bg-orange-600 font-medium py-1 px-2 mr-4 rounded-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110 flex items-center"><i
                            class="fas fa-edit mr-2"></i>{{
                                $t('change-password') }}</button>
                    <button @click="goBack"
                        class="bg-gray-300 hover:bg-gray-500 text-black font-medium py-1 px-2 rounded-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110 flex items-center">
                        <i class="fas fa-times mr-2"></i> {{
                            $t('cancel') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!--Modal para cambiar la imagen-->
    <TransitionRoot as="template" :show="modal_image">
        <Dialog as="div" class="fixed z-50 inset-0 overflow-y-auto" @close="modal_image = false">
            <div class="flex items-center justify-center min-h-screen px-4">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100"
                    leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                    <DialogOverlay class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
                </TransitionChild>

                <TransitionChild as="template" enter="ease-out duration-300"
                    enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200"
                    leave-from="opacity-100 translate-y-0 sm:scale-100"
                    leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    <div
                        class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full md:w-1/2 lg:w-1/3">
                        <form>
                            <div class="p-4">
                                <div class="mb-4">
                                    <label for="image" class="block text-gray-700 mb-2 center"><i
                                            class="fas fa-images mr-2"></i>{{
                                                $t('select-image')
                                            }}</label>
                                    <input type="file" id="image" name="image"
                                        class="border-gray-300 rounded-md w-full py-2 px-3">
                                </div>
                                <div class="flex justify-end">
                                    <button
                                        class="bg-orange-400 hover:bg-orange-600 font-medium py-1 px-2 rounded-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110 ml-auto flex items-center"
                                        @click="changeImage()">
                                        <i class="far fa-save mr-2"></i>{{ $t('save') }}
                                    </button>
                                    <button type="button"
                                        class="bg-gray-300 hover:bg-gray-400 text-black font-medium py-1 px-1 rounded-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110 mr-2 ml-4 flex items-center"
                                        @click="closeModal()">
                                        <i class="fas fa-times mr-2"></i>{{ $t('cancel') }}
                                    </button>

                                    <button type="button"
                                        class="bg-red-500 hover:bg-red-700 text-white font-medium py-1 px-3 rounded-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110 mr-2 ml-4 flex items-center"
                                        @click="deleteImage()">
                                        <i class="fas fa-trash-alt mr-2"></i>{{ $t('delete') }}
                                    </button>


                                </div>

                            </div>
                        </form>
                    </div>
                </TransitionChild>
            </div>
        </Dialog>
    </TransitionRoot>
    <!--<><>-->
    <!--<> Modal para cambiar la contraseña del usuario<>-->
    <TransitionRoot as="template" :show="modal_password">
        <Dialog as="div" class="fixed z-50 inset-0 overflow-y-auto" @close="modal_password = false">
            <div class="flex items-center justify-center min-h-screen px-4">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100"
                    leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                    <DialogOverlay class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
                </TransitionChild>

                <TransitionChild as="template" enter="ease-out duration-300"
                    enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200"
                    leave-from="opacity-100 translate-y-0 sm:scale-100"
                    leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    <div
                        class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full md:w-1/2 lg:w-1/3">
                        <form>
                            <div class="p-4">
                                <div class="mb-4">
                                    <label for="image" class="block text-gray-700 font-bold mb-2"><i
                                            class="fas fa-edit mr-2"></i> {{
                                                $t('change-password')
                                            }}</label>
                                    {{ $t('password') }}
                                    <i :class="actualPasswordVisible ? 'far fa-eye' : 'far fa-eye-slash'"
                                        @click="togglePasswordVisibility('actualPassword')"></i>

                                    <input type="password" id="actualPassword" name="actualPassword"
                                        class="border-gray-300 rounded-md w-full px-3 mb-5">
                                    {{ $t('new-password') }}
                                    <i :class="newPasswordVisible ? 'far fa-eye' : 'far fa-eye-slash'"
                                        @click="togglePasswordVisibility('newPassword')"></i>

                                    <input type="password" id="newPassword" name="newPassword"
                                        class="border-gray-300 rounded-md w-full py-2 px-3 mb-5">
                                    {{ $t('confirm-new-password') }}
                                    <i :class="confirmNewPasswordVisible ? 'far fa-eye' : 'far fa-eye-slash'"
                                        @click="togglePasswordVisibility('confirmNewPassword')"></i>

                                    <input type="password" id="confirmNewPassword" name="confirmNewPassword"
                                        class="border-gray-300 rounded-md w-full py-2 px-3"
                                        :class="{ 'border-red-500': error !== '' }">
                                    <div v-if="error" class="text-red-500 mt-2">
                                        {{ error }}
                                    </div>


                                </div>
                                <div class="flex justify-end">

                                    <button
                                        class="bg-orange-400 hover:bg-orange-600 font-medium py-1 px-2 rounded-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110 ml-auto flex items-center"
                                        @click="changePassword()">
                                        <i class="far fa-save mr-2"></i>{{ $t('save') }}
                                    </button>
                                    <button type="button"
                                        class="bg-gray-300 hover:bg-gray-400 text-black font-medium py-1 px-1 rounded-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110 mr-2 ml-4 flex items-center"
                                        @click="closeModalPassword()">
                                        <i class="fas fa-times mr-2"></i>{{ $t('cancel') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </TransitionChild>
            </div>
        </Dialog>
    </TransitionRoot>
    <!--<> Fin del modal <>-->
</template>
<script>
import axios from 'axios';

export default {
    data() {
        return {
            user: {
                id: '',
                name: '',
                last_name: '',
                email: '',
                phone: '',
                nick_name: '',
                profile_image: '',
                company_id: '',
                company_name: ''
            },

            companies: [],
            showModal: false,
            modal_image: false,
            modal_password: false,
            actualPasswordVisible: false,
            newPasswordVisible: false,
            confirmNewPasswordVisible: false,
            error: ''
        };
    },
    mounted() {
        axios.get('/user-info').then(response => {
            Object.assign(this.user, response.data[0])
        })

        axios.get('/company/listAllCompanies').then(response => {
            this.companies = response.data
        })
    },

    methods: {
        updateUserInfo() {
            let userData = {
                name: this.user.name,
                last_name: this.user.last_name,
                nick_name: this.user.nick_name,
                email: this.user.email,
                phone: this.user.phone,
                company_id: this.user.company_id
            };

            axios.post('/PerfilPersonal_Admin/Editar_Perfil/update', userData)
                .then(response => {
                    window.location.href = '/PerfilPersonal_Admin';
                
                })
                .catch(error => {
                    console.error(error);
                    console.log(userData)
                    alert('Error al actualizar la información del usuario. Por favor, inténtelo de nuevo más tarde.');
                });
        },

        //funcio
        changeImage() {
            // Agafem la imatge del imput
            //Sens guardara la imatge que selecionem a la variable image
            const image = document.querySelector('#image').files[0];

            // Crear un objeto FormData y agregar la imagen al fromData (objecte)
            const formData = new FormData();
            //append es una funcio de javascript
            formData.append('profile_image', image);

            // Enviar la petición POST en Axios
            axios.post('/update-profile-image', formData)
                .then(response => {
                    //Mostra un missatge per consola
                    console.log(response.data);
                    //Actualizar la pagina
                    window.location.reload();
                })
                //En cas de erro surtira este missatge per consola
                .catch(error => {
                    console.log(error);
                });
        },

        deleteImage() {
            //Ens surtira un missatge de confirmacio
            if (confirm(this.$t('confirmDeleteImage'))) {
                //this.$t es per fer la traducio
                // Enviar la petición POST en Axios
                axios.post('/delete-profile-image')
                    .then(response => {
                        window.location.reload();
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        },

        changePassword() {
            //es per a evitar que senvie per defecte les dades
            preventDefault();
            //Variables que agafen la contraseña que posem
            //('actualPassword') son ides
            const currentPassword = document.getElementById('actualPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmNewPassword = document.getElementById('confirmNewPassword').value;

            // Validaciones
            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

            //comproacions
            if (newPassword !== confirmNewPassword) {
                this.error = this.$t('passwordsDiferents');
                return;
            } else if (!passwordRegex.test(newPassword)) {
                this.error = this.$t('rejexPassword');
                return;
            }

            //Ens envia una peticio post en axio per a fer la funcio de cambiar contraseña
            axios.post('/change-password', {
                current_password: currentPassword,
                new_password: newPassword,
                confirm_password: confirmNewPassword
            })
                .then(response => {
                    // Redirigir al usuario a la página de perfil con un mensaje de éxito
                    alert(this.$t('updatePasswordMsg'));
                    this.closeModalPassword(); // Cerrar el modal
                })
                .catch(error => {
                    console.log(error);
                    alert('No se ha podido cambiar la contraseña. Inténtelo de nuevo más tarde.');
                });
        },
        //La icona del ull per a mostrar i ocultar la contrasenya
        togglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                if (inputId === 'actualPassword') {
                    this.actualPasswordVisible = true;
                } else if (inputId === 'newPassword') {
                    this.newPasswordVisible = true;
                } else {
                    this.confirmNewPasswordVisible = true;
                }
            } else {
                input.type = "password";
                if (inputId === 'actualPassword') {
                    this.actualPasswordVisible = false;
                } else if (inputId === 'newPassword') {
                    this.newPasswordVisible = false;
                } else {
                    this.confirmNewPasswordVisible = false;
                }
            }
        },

        openModal() {
            this.modal_image = true;
        },

        openModalPassword() {
            this.modal_password = true;
        },


        closeModalPassword() {
            this.modal_password = false;
        },

        closeModal() {
            this.modal_image = false;
        },
        goBack() {
            window.history.back();
        },
    }

};
</script>
<script setup>
//Script setup es per a que funcionigue el modal de headles
import { Dialog, DialogOverlay, TransitionChild, TransitionRoot } from '@headlessui/vue'
</script>
   
<style scoped>
td {
    padding: 7px;
}

table {
    font-size: 15px;
    width: 100%;
    height: 200%;
}

.container {
    display: flex;
    align-items: center;
    justify-content: center;
}

.left-table {
    height: 100%;
}

.card {
    height: 100%;
}

.main-body {
    padding: 15px;
}

.overflow-x-auto {
    overflow-x: auto;
}

.form-input {
    border: 1px solid #d2d6dc;
    border-radius: 0.375rem;
    color: #1f2937;
    display: block;
    font-size: 0.875rem;
    line-height: 1.25rem;
    padding: 0.5rem;
    width: 100%;
}
</style>