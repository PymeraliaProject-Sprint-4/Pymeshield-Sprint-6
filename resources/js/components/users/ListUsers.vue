<template>
    <div class="flex items-center justify-center flex-col">
        <h1 class="mt-5 text-center text-2xl font-semibold">{{ $t('list-users') }}</h1>
        <div class="flex justify-between mt-5 w-full">
            <div class="flex items-center">
                <div class="ml-5">
                    <button
                        class="text-black font-bold focus:ring-4 focus:outline-none rounded flex items-center text-center h-6 px-2">
                        {{ $t('order-by') }}
                        <select v-model="selectedType" @change="sortTable" class="ml-2">
                            <option value="worker">{{ $t('Worker') }}</option>
                            <option value="admin" selected>{{ $t('Admin') }}</option>
                            <option value="client">{{ $t('Client') }}</option>
                        </select>
                    </button>
                </div>
                <div class="mb-4 ml-10 ">
                    <input v-model="searchTerm" type="text"
                        class="mt-5 px-4 py-2 border rounded-lg w-60 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        :placeholder="$t('Search')" />
                </div>
            </div>
            <div class=" mr-12 flex items-center">
                <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded flex items-center"
                    @click="openModalCrear()">
                    <PlusIcon class="h-6 w-6 text-white-400" aria-hidden="true" />
                    <span class="ml-2">{{ $t('create-user') }}</span>
                </button>
                <button @click="redirectToDeletedUser()" id=""
                    class="ml-4 text-white bg-orange-500 hover:bg-orange-700 font-bold focus:ring-4 focus:outline-none py-2 px-4 rounded flex items-center text-center">
                    <EyeSlashIcon class="h-6 w-6 text-white-400 font-bold" />
                    {{ $t('Deleted.User') }}
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="p-6 text-gray-900">
                <div class="mb-4">
                    <div id="margin_table" class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full shadow-lg rounded-lg">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                <tr class="bg-orange-400">
                                    <th scope="col" class="text-xs text-white uppercase p-4">
                                        {{ $t('name') }}
                                    </th>
                                    <th scope="col" class="text-xs text-white uppercase p-4">
                                        {{ $t('lastname') }}
                                    </th>
                                    <th scope="col" class="text-xs text-white uppercase p-4">
                                        {{ $t('email') }}
                                    </th>
                                    <th scope="col" class="text-xs text-white uppercase p-4">
                                        {{ $t('phone') }}
                                    </th>
                                    <th scope="col" class="text-xs text-white uppercase p-4">
                                        {{ $t('company') }}
                                    </th>
                                    <th scope="col" class="text-xs text-white uppercase p-4">
                                        {{ $t('functions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-orange-50 hover:bg-orange-100 text-center" v-for="users in filteredUsers">
                                    <td class="break-all px-6 py-4 font-medium text-gray-900">
                                        {{ users.name }}
                                    </td>
                                    <td class="break-all px-6 py-4 font-medium text-gray-900">
                                        {{ users.last_name }}
                                    </td>
                                    <td class="break-all px-6 py-4 font-medium text-gray-900">
                                        {{ users.email }}
                                    </td>
                                    <td class="break-all px-6 py-4 font-medium text-gray-900">
                                        {{ users.phone }}
                                    </td>
                                    <td class="break-all px-6 py-4 font-medium text-gray-900">
                                        {{ users.company_name }}
                                    </td>
                                    <td class="px-4 py-4 text-center align-middle">
                                        <div class="inline-flex">
                                            <button
                                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-2 ml-2 rounded"
                                                @click="openModalBaja(users)">
                                                <TrashIcon class="h-6 w-6 text-white-400" aria-hidden="true" />
                                            </button>
                                            <button
                                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-2 ml-2 rounded"
                                                @click="openModalEditar(users)">
                                                <PencilSquareIcon class="h-6 w-6 text-white-400" aria-hidden="true" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>




                        <div class="flex justify-center items-center mt-8" v-if="searchTerm == ''">


                            <div class="flex justify-center items-center">
                                <nav class="bg-white p-6 rounded-lg shadow-lg">
                                    <ul class="flex space-x-4">
                                        <li v-if="pagination.length > 0 && currentPage > 1">
                                            <button @click="changePage(currentPage - 1)"
                                                class="bg-gray-200 hover:bg-gray-300 px-3 py-2 rounded-md shadow-md">
                                                {{ $t('Previous') }}
                                            </button>
                                        </li>
                                        <li v-for="page in Math.ceil(user.length / perPage)" :key="page">
                                            <button @click="changePage(page)"
                                                class="bg-gray-200 hover:bg-gray-300 px-3 py-2 rounded-md shadow-md"
                                                :class="{ 'bg-orange-400': page === currentPage }">
                                                {{ page }}
                                            </button>
                                        </li>
                                        <li v-if="pagination.length > 0 && currentPage < Math.ceil(user.length / perPage)">
                                            <button @click="changePage(currentPage + 1)"
                                                class="bg-gray-200 hover:bg-gray-300 px-3 py-2 rounded-md shadow-md">
                                                {{ $t('Next') }}
                                            </button>
                                        </li>
                                    </ul>
                                </nav>
                            </div>

                        </div>


                        <!--Modal Crear usaurio-->
                        <TransitionRoot as="template" :show="ModalCrear">
                            <Dialog as="div" class="relative z-10" @close="ModalCrear = false">
                                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100"
                                    leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
                                </TransitionChild>
                                <div class="fixed inset-0 z-10 overflow-y-auto">
                                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                        <TransitionChild as="template" enter="ease-out duration-300"
                                            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                            enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200"
                                            leave-from="opacity-100 translate-y-0 sm:scale-100"
                                            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                                            <DialogPanel
                                                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                    <div class="xl:items-start">
                                                        <div class="flex space-x-2 items-center">

                                                            <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                                                                {{ $t('create-user') }}</DialogTitle>
                                                        </div>
                                                        <div class="mt-3 text-center md:text-left">
                                                            <div class="mt-2">
                                                                <div class="relative">
                                                                    <label for="name"
                                                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">{{
                                                                            $t('name') }}</label>
                                                                    <input type="text" v-model="name" id="name"
                                                                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-orange-400 focus:border-orange-400 pr-8"
                                                                        required>
                                                                    <span v-show="!name"
                                                                        class="absolute right-0 top-8 mt-2 mr-2 text-red-500">*</span>
                                                                </div>
                                                            </div>
                                                            <div class="mt-2">
                                                                <div class="relative">
                                                                    <label for="last_name"
                                                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">{{
                                                                            $t('lastname') }}</label>
                                                                    <input type="text" v-model="last_name" id="last_name"
                                                                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-orange-400 focus:border-orange-400 pl-6"
                                                                        required>
                                                                    <span v-if="!last_name"
                                                                        class="absolute right-0 top-8 mt-2 mr-2 text-red-500">*</span>
                                                                </div>
                                                            </div>
                                                            <div class="mt-2">
                                                                <div class="relative">
                                                                    <div>
                                                                        <label for="nick_name"
                                                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">{{
                                                                                $t('username') }}</label>
                                                                        <input type="text" v-model="nick_name" id="nick_name"
                                                                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-orange-400 focus:border-orange-400 pl-6"
                                                                            required>
                                                                        <span v-if="!nick_name"
                                                                            class="absolute right-0 top-8 mt-2 mr-2 text-red-500">*</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="mt-2">
                                                                <div class="relative">
                                                                    <div class="flex w-full space-x-8">
                                                                        <div class="w-full">
                                                                            <label for="email"
                                                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">{{
                                                                                    $t('email') }}</label>
                                                                            <input type="text" v-model="email" id="email"
                                                                                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-orange-400 focus:border-orange-400 pl-6"
                                                                                required>
                                                                            <span v-if="!email"
                                                                                class="absolute right-0 top-8 mt-2 mr-2 text-red-500">*</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="w-full">
                                                                        <div class="relative">
                                                                            <label for="phone"
                                                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">{{
                                                                                    $t('phone') }}</label>
                                                                            <input type="text" v-model="phone" id="phone"
                                                                                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-orange-400 focus:border-orange-400 pl-6"
                                                                                required>
                                                                            <span v-if="!phone"
                                                                                class="absolute right-0 top-8 mt-2 mr-2 text-red-500">*</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="w-full">
                                                                    <div class="relative">
                                                                        <label for="company_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">{{ $t('company') }}</label>
                                                                        <select v-model="selectedCompany" id="company_name" class="w-full h-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-orange-400 focus:border-orange-400 pl-6" required>
                                                                            <option value="" disabled selected>{{ $t('select_company') }}</option>
                                                                            <option v-for="company in companies" :key="company.id" :value="company.id">{{ company.name }}</option>
                                                                        </select>
                                                                        <span v-if="!selectedCompany" class="absolute right-0 top-8 mt-2 mr-2 text-red-500">*</span>
                                                                    </div>                                                                    
                                                                </div>
                                                                <div class="w-full">
                                                                    <div class="relative">
                                                                        <label for="rols"
                                                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">{{
                                                                                $t('Role') }}</label>
                                                                        <select v-model="selectedType" id="rols"
                                                                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-orange-400 focus:border-orange-400 pl-6"
                                                                            required>
                                                                            <option value="" disabled selected>{{ $t('Select a role') }}
                                                                            </option>
                                                                            <option value="worker">{{ $t('Worker') }}</option>
                                                                            <option value="admin">{{ $t('Admin') }}</option>
                                                                            <option value="client">{{ $t('Client') }}</option>
                                                                        </select>
                                                                        <span v-if="!selectedType"
                                                                            class="absolute right-0 top-8 mt-2 mr-2 text-red-500">*</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mt-2">
                                                            <div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                                    <button type="button"
                                                        class="bg-gray-300 hover:bg-gray-500 text-black font-medium py-1 px-2 rounded-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110 "
                                                        @click="closeModalCrear()"><i class="fas fa-times mr-2"></i>{{
                                                            $t('cancel') }}</button>
                                                    <button
                                                        class="bg-orange-400 hover:bg-orange-600 font-medium py-1 px-2 mr-4 rounded-lg  transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110 ml-auto flex items-center"
                                                        @click="createUser()"><i class="fas fa-plus mr-2"></i> {{ $t('add') }}</button>
                                                </div>
                                            </DialogPanel>
                                        </TransitionChild>
                                    </div>
                                </div>
                            </Dialog>
                        </TransitionRoot>

                        <!--Modal editar Usuario-->
                        <TransitionRoot as="template" :show="ModalEditar">
                            <Dialog as="div" class="relative z-10" @close="ModalEditar = false">
                                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100"
                                    leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
                                </TransitionChild>
                                <div class="fixed inset-0 z-10 overflow-y-auto">
                                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                        <TransitionChild as="template" enter="ease-out duration-300"
                                            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                            enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200"
                                            leave-from="opacity-100 translate-y-0 sm:scale-100"
                                            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                                            <DialogPanel
                                                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                    <div class="xl:items-start">
                                                        <div class="flex space-x-2 items-center">
                                                            <div
                                                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center sm:mx-0 sm:h-10 sm:w-10">
                                                                <i class="fas fa-edit mr-2"></i>
                                                            </div>
                                                            <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900"> {{
                                                                $t('edituser') }}</DialogTitle>
                                                        </div>
                                                        <div class="mt-3 text-center md:text-left">
                                                            <form>
                                                                <div class="mt-2">
                                                                    <div class="relative">
                                                                        <label for="name"
                                                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">{{
                                                                                $t('name') }}</label>
                                                                        <input type="text" v-model="currentUser.name" id="name" name="name"
                                                                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-orange-400 focus:border-orange-400 pr-8"
                                                                            required>
                                                                        <span v-if="!currentUser.name"
                                                                            class="absolute right-0 top-8 mt-2 mr-2 text-red-500">*</span>
                                                                    </div>
                                                                </div>
                                                                <div class="mt-2">
                                                                    <div class="relative">
                                                                        <label for="last_name"
                                                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">{{
                                                                                $t('lastname') }}</label>
                                                                        <input type="text" v-model="currentUser.last_name" id="last_name"
                                                                            name="last_name"
                                                                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-orange-400 focus:border-orange-400 pr-8"
                                                                            required>
                                                                        <span v-if="!currentUser.last_name"
                                                                            class="absolute right-0 top-8 mt-2 mr-2 text-red-500">*</span>
                                                                    </div>
                                                                </div>
                                                                <div class="mt-2">
                                                                    <div class="relative">
                                                                        <label for="nick_name"
                                                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">{{
                                                                                $t('username') }}</label>
                                                                        <input type="text" v-model="currentUser.nick_name" id="nick_name"
                                                                            name="nick_name"
                                                                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-orange-400 focus:border-orange-400 pr-8"
                                                                            required>
                                                                        <span v-if="!currentUser.nick_name"
                                                                            class="absolute right-0 top-8 mt-2 mr-2 text-red-500">*</span>
                                                                    </div>
                                                                </div>
                                                                <div class="mt-2">
                                                                    <div class="flex w-full space-x-8">
                                                                        <div class="w-full relative">
                                                                            <label for="email"
                                                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">{{
                                                                                    $t('email') }}</label>
                                                                            <input type="email" v-model="currentUser.email" id="email"
                                                                                name="email"
                                                                                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-orange-400 focus:border-orange-400 pr-8"
                                                                                required>
                                                                            <span v-if="!currentUser.email"
                                                                                class="absolute right-0 top-8 mt-2 mr-2 text-red-500">*</span>
                                                                        </div>
                                                                        <div class="w-full relative">
                                                                            <label for="phone"
                                                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">{{
                                                                                    $t('phone') }}</label>
                                                                            <input type="text" v-model="currentUser.phone" id="phone"
                                                                                name="phone"
                                                                                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-orange-400 focus:border-orange-400 pr-8"
                                                                                required>
                                                                            <span v-if="!currentUser.phone"
                                                                                class="absolute right-0 top-8 mt-2 mr-2 text-red-500">*</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="relative">
                                                                        <label for="company_name"
                                                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">{{
                                                                                $t('company') }}</label>
                                                                        <select v-model="currentUser.company_name" id="company_name"
                                                                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-orange-400 focus:border-orange-400 pl-6"
                                                                            required>
                                                                            <option value="" disabled selected>{{ $t('select_company') }}
                                                                            </option>
                                                                            <option v-for="company in companies" :key="company.id"
                                                                                :value="company.name">{{ company.name }}</option>
                                                                        </select>
                                                                        <span v-if="!currentUser.company_name"
                                                                            class="absolute right-0 top-8 mt-2 mr-2 text-red-500">*</span>
                                                                    </div>
                                                                </div>
                                                                <div class="w-full">
                                                                    <div class="relative">
                                                                        <label for="rols"
                                                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">{{
                                                                                $t('Role') }}</label>
                                                                        <select v-model="currentUser.type" id="rols"
                                                                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-orange-400 focus:border-orange-400 pl-6"
                                                                            required>
                                                                            <option value="" disabled selected>{{ $t('Select a role') }}
                                                                            </option>
                                                                            <option value="worker">{{ $t('Worker') }}</option>
                                                                            <option value="admin">{{ $t('Admin') }}</option>
                                                                            <option value="client">{{ $t('Client') }}</option>
                                                                        </select>
                                                                        <span v-if="!currentUser.type"
                                                                            class="absolute right-0 top-8 mt-2 mr-2 text-red-500">*</span>
                                                                    </div>
                                                                </div>
                                                                <div class="mt-2">
                                                                    <div>

                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                                    <button type="button"
                                                        class="bg-gray-300 hover:bg-gray-500 text-black font-medium py-1 px-2 rounded-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110 "
                                                        @click="closeModal()" ref="cancelButtonRef"><i class="fas fa-times mr-2"></i>{{
                                                            $t('cancel') }}
                                                    </button>
                                                    <button type="button"
                                                        class="bg-orange-400 hover:bg-orange-600 font-medium py-1 px-2 mr-4 rounded-lg  transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110 ml-auto flex items-center"
                                                        @click="submitFormEditar()"> <i class="far fa-save mr-2"></i>{{ $t('save') }}
                                                    </button>
                                                </div>
                                            </DialogPanel>
                                        </TransitionChild>
                                    </div>
                                </div>
                            </Dialog>
                        </TransitionRoot>

                        <!--Modal baja Usuario-->
                        <TransitionRoot as="template" :show="ModalBaja">
                            <Dialog as="div" class="relative z-10" @close="ModalBaja = false">
                                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100"
                                    leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
                                </TransitionChild>

                                <div class="fixed inset-0 z-10 overflow-y-auto">
                                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                        <TransitionChild as="template" enter="ease-out duration-300"
                                            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                            enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200"
                                            leave-from="opacity-100 translate-y-0 sm:scale-100"
                                            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                                            <DialogPanel
                                                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                                                <form>
                                                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                        <div class="xl:items-start">
                                                            <div class="flex space-x-2 items-center">
                                                                <div
                                                                    class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center sm:mx-0 sm:h-10 sm:w-10">
                                                                    <i class="fas fa-edit mr-2"></i>
                                                                </div>
                                                                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                                                                    {{ $t('deregister user') }}</DialogTitle>
                                                            </div>

                                                            <div class="mt-3 text-center md:text-left">

                                                                <div class="mt-2">
                                                                    <div>
                                                                        <label
                                                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{
                                                                                $t('deregistration-reason') }}</label>
                                                                        <textarea maxlength="255" v-model="currentUser.removed_reason"
                                                                            id="removed_reason" name="removed_reason"
                                                                            class="w-full h-24 align-top bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-orange-400 focus:border-orange-400"
                                                                            cols="auto" rows="10"></textarea>
                                                                        <input type="hidden" v-model="currentUser.id">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                                        <button type="button"
                                                            class="bg-gray-300 hover:bg-gray-500 text-black font-medium py-1 px-2 rounded-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110 "
                                                            @click="closeModalBaja()" ref="cancelButtonRef"><i class="fas fa-times mr-2"></i>{{
                                                                $t('cancel') }}</button>
                                                        <button type="button"
                                                            class="bg-red-600 hover:bg-red-800 text-white font-medium py-1 px-2 rounded-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110 mr-6"
                                                            @click="submitFormBaja()"><i class="fas fa-minus mr-2"></i>{{ $t('Deregister')
                                                            }}</button>
                                                    </div>
                                                </form>
                                            </DialogPanel>
                                        </TransitionChild>
                                    </div>
                                </div>
                            </Dialog>
                        </TransitionRoot>

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
            user: [],
            companies: [],
            currentUser: {},
            ModalEditar: false,
            ModalBaja: false,
            ModalCrear: false,
            pagination: {},
            page: 1,
            perPage: 1, // cantidad de elementos por página
            currentPage: 1, // página actual
            selectedType: '',
            selectedCompany: '',
            searchTerm: '',
        };
    },

    mounted() {
        this.getUsers(this.currentPage);
        this.selectedType = 'admin';
    },
    methods: {
        getUsers(page = 1) {
            axios.get(`userList/userListing/Admin?page=${page}`)
                .then(response => {
                    this.user = response.data.data;
                    this.currentPage = page;
                })
                .catch(error => {
                    console.log(error);
                });

            axios.get(`/listadoEmpresas/listCompanies`)
                .then((response) => {
                    this.companies = response.data.data;

                })
                .catch((error) => {
                    console.log(error);
                })
        },

        changePage(page) {
            this.getUsers(page);
        },

        sortTable() {
            let url = '';
            switch (this.selectedType) {
                case 'admin':
                    url = 'userList/userListing/Admin';
                    break;
                case 'worker':
                    url = 'userList/userListing/Worker';
                    break;
                case 'client':
                    url = 'userList/userListing/Client';
                    break;
            }
            axios.get(url)
                .then(response => {
                    this.user = response.data.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },

        openModalCrear() {
            this.ModalCrear = true;
        },

        createUser() {
            axios.post('/addUser', {
                name: this.name,
                last_name: this.last_name,
                nick_name: this.nick_name,
                email: this.email,
                phone: this.phone,
                selectedCompany: this.selectedCompany,
                selectedType: this.selectedType,
            })
                .then(async response => {
                    this.name = '';
                    this.last_name = '';
                    this.nick_name = '';
                    this.email = '';
                    this.phone = '';
                    this.selectedCompany = '';
                    this.selectedType = '';
                    this.ModalCrear = false;
                    this.getUsers();
                })
                
                .catch(error => {
                    console.log(error.response)
                })
        },

        closeModalCrear() {
            this.ModalCrear = false;
        },
        openModalEditar(user) {
            this.ModalEditar = true;
            this.currentUser = {
                id: user.id,
                name: user.name,
                last_name: user.last_name,
                nick_name: user.nick_name,
                email: user.email,
                phone: user.phone,
                type: user.type,
                company_name: user.company_name
            };
        },

        closeModal() {
            this.ModalEditar = false;
            this.currentUser = {};
        },

        submitFormEditar() {
            axios.post("userList/editUser", {
                id: this.currentUser.id,
                name: this.currentUser.name,
                last_name: this.currentUser.last_name,
                nick_name: this.currentUser.nick_name,
                email: this.currentUser.email,
                phone: this.currentUser.phone,
                type: this.currentUser.type,
                company_name: this.currentUser.company_name
            })
                .then(response => {
                    this.getUsers();
                    console.log(response);
                    this.ModalEditar = false;
                    this.currentUser = {};
                    this.NotificacionEditar = true;
                    setTimeout(() => { this.NotificacionEditar = false; }, 2000);
                })

                .catch(error => {
                    console.error(error);
                });
        },



        openModalBaja(user) {
            this.ModalBaja = true;
            this.currentUser = {
                id: user.id,
                removed_reason: user.removed_reason
            };
        },
        submitFormBaja() {
            axios.post("userList/unuscribeUser", {
                id: this.currentUser.id,
                removed_reason: this.currentUser.removed_reason,
            })
                .then((response) => {
                    this.getUsers();
                    console.log(response);
                    this.ModalBaja = false;
                })
                .catch((error) => {
                    console.error(error);
                });
        }

        ,
        closeModalBaja() {
            this.ModalBaja = false;
            this.currentUser = {};
        },

        redirectToDeletedUser() {
            window.location.href = "/userListhidden";
        }
    },
    computed: {
        filteredUsers() {
            const searchTermLower = this.searchTerm.toLowerCase();
            return this.user.filter(users => {
                const userString = users.name.toLowerCase() + users.last_name.toLowerCase() + users.email.toLowerCase() + users.phone.toLowerCase() + users.company_name.toLowerCase();
                return userString.includes(searchTermLower);
            });
        }

    },
}
</script>

<script setup>
import { PencilSquareIcon, TrashIcon, PlusIcon, EyeSlashIcon } from '@heroicons/vue/24/outline'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'

</script>