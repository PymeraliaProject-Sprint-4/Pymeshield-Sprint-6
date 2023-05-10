<template>
    <div class="container">
        <div class="main-body p-3 overflow-x-auto">
            <div class="row gutters-sm">
                <div class="col-md-4 mb-3 left-table">
                    <div class="card">
                        <div class="card-body">

                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="mt-5 font-bold" style="text-align: center;">{{ $t('personal-info') }}</h3>
                    <div class="overflow-x-auto">

                        <table class="table">
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
                                <tr  v-for="userInfo in user">
                                <td class="bg-orange-50 text-center">
                                    <p class="font-medium text-gray-900">{{ userInfo.name }}</p>
                                </td>
                                <td class="bg-orange-50 text-center">
                                    <p class="font-medium text-gray-900">{{ userInfo.last_name }}</p>
                                </td>
                                <td class="bg-orange-50 text-center">
                                    <p class="font-medium text-gray-900">{{ userInfo.nick_name }}</p>
                                </td>
                                <td class="bg-orange-50 text-center">
                                    <p class="font-medium text-gray-900">{{ userInfo.email }}</p>
                                </td>
                                <td class="bg-orange-50 text-center">
                                    <p class="font-medium text-gray-900">{{ userInfo.phone }}</p>
                                </td>
                                <td class="bg-orange-50 text-center">
                                    <p class="font-medium text-gray-900">{{ userInfo.company_name }}</p>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </div>


                    <div class="flex">
                        <a href="/PerfilPersonal_Worker/EditarPerfilWorker">
                            <button
                                class="bg-orange-400 hover:bg-orange-600 text-black font-medium py-2 px-4 rounded-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110 mx-2 mt-3 ml-auto">
                                <i class="fas fa-edit mr-2"></i> {{ $t('edit-profile') }}
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
import axios from 'axios';


export default {
    data() {
        return {
            user: {},
        };
    },
    mounted() {
        axios.get('/user-info').then(response => {
            this.user = response.data;
        });
    },
};
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
    text-align: center;
}
</style>
