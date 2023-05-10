<template>
    <h1 class="mt-5 ml-9 mb-4 text-3xl font-extrabold text-gray-900 dark:text-white md:text-5xl lg:text-6xl"><span
            class="text-transparent bg-clip-text bg-gradient-to-r to-orange-500 from-orange-300">{{ $t('Admin') }}</span>
    </h1>
    <p class="ml-9 text-lg font-normal text-gray-500 lg:text-xl dark:text-gray-400">{{ $t('phrase.1.categories') }}
    </p>

    <div style="display: flex; justify-content: center;">
        <!-- Modal toggle -->
        <button id="" @click="modal_crear_categoria = true"
            class=" bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mt-5 ml-5 flex items-center"
            type="button">
            <PlusIcon class="h-6 w-6 text-white-400 font" aria-hidden="true" />
            {{ $t('Add') }} {{ $t('category-name') }}
        </button>

        <button id="" @click="modal_crear_activitat = true"
            class=" bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mt-5 ml-5 flex items-center"
            type="button">
            <PlusIcon class="h-6 w-6 text-white-400 font" aria-hidden="true" />
            {{ $t('Add') }} {{ $t('activity-name') }}
        </button>

        <button id="" @click="redirectToCourses()"
            class=" ml-5 text-white bg-orange-500 hover:bg-orange-700 font-bold focus:ring-4 focus:outline-none py-2 px-4 rounded mt-5 flex items-center text-center ">
            <ArrowLeftCircleIcon class="h-6 w-6 text-white-400 font-bold" />
            {{ $t('Courses') }}
        </button>

        <button id="" @click="redirectToRescources()"
            class=" ml-5 text-white bg-orange-500 hover:bg-orange-700 font-bold focus:ring-4 focus:outline-none py-2 px-4 rounded mt-5 flex items-center text-center ">
            <ArrowRightCircleIcon class="h-6 w-6 text-white-400 font-bold" />
            {{ $t('Rescources') }}
        </button>

    </div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="p-6 text-gray-900">
                <div class="mb-4">
                    <input v-model="searchTerm" type="text"
                        class="px-4 py-2 border rounded-lg w-full text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        :placeholder="$t('Search')" />
                </div>
                <div id="margin_table" class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full shadow-lg rounded-lg">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                            <tr class="bg-orange-400">
                                <th scope="col" class="text-xs text-white uppercase p-4">
                                    {{ $t('Categories') }}
                                </th>
                                <th scope="col" class="text-xs text-white uppercase p-4">
                                    {{ $t('Options') }}

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-orange-50 hover:bg-orange-100 text-center" v-for="category in filteredCategories"
                                :key="category.id">
                                <td class="break-all px-6 py-4 font-medium text-gray-900">
                                    {{ category.name }}
                                </td>
                                <td>
                                    <div class="inline-flex">
                                        <!-- Modal toggle -->
                                        <button
                                            class="bg-blue-500 hover:bg-blue-700  text-white font-bold py-2 px-2 ml-2 rounded"
                                            @click="ModalEditCategory(category)">
                                            <PencilSquareIcon class="h-6 w-6 text-white-400" aria-hidden="true" />
                                        </button>
                                        <button
                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-2 ml-2 rounded "
                                            @click="ModalEliminarCategoria(category)">
                                            <TrashIcon class="h-6 w-6 text-white-400" aria-hidden="true" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="flex justify-center items-center mt-8">
                        <nav class="bg-white p-6 rounded-lg shadow-lg">
                            <ul class="flex space-x-4">
                                <li v-if="pagination.length > 0 && currentPage > 1">
                                    <button @click="changePage(currentPage - 1)"
                                        class="bg-gray-200 hover:bg-gray-300 px-3 py-2 rounded-md shadow-md">
                                        {{ $t('Previous') }}
                                    </button>
                                </li>
                                <li v-for="page in Math.ceil(categories.length / perPage)" :key="page">
                                    <button @click="changePage(page)"
                                        class="bg-gray-200 hover:bg-gray-300 px-3 py-2 rounded-md shadow-md"
                                        :class="{ 'bg-orange-400': page === currentPage }">
                                        {{ page }}
                                    </button>
                                </li>
                                <li v-if="pagination.length > 0 && currentPage < Math.ceil(categories.length / perPage)">
                                    <button @click="changePage(currentPage + 1)"
                                        class="bg-gray-200 hover:bg-gray-300 px-3 py-2 rounded-md shadow-md">
                                        {{ $t('Next') }}
                                    </button>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--MODAL CREAR Activitat-->
    <TransitionRoot as="template" :show="modal_crear_activitat">
        <Dialog as="div" class="relative z-10" @close="modal_crear_activitat = false">
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
                            <form action="" id="category-form" method="POST">
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="flex items-center mx-1 my-1">
                                        <div
                                            class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                            <AcademicCapIcon class="h-6 w-6 text-orange-400" aria-hidden="true" />
                                        </div>
                                        <DialogTitle class="ml-2 flex text-base font-semibold leading-6 text-gray-900">
                                            {{ $t('Create') }} {{ $t('new.2') }} {{ $t('activity-name') }}
                                        </DialogTitle>
                                    </div>
                                    <div class="sm:items-start">
                                        <div class="mt-3 text-center sm:mt-0 sm:mx-1 sm:text-left">
                                            <div hidden>
                                                @csrf
                                            </div>
                                            <div>
                                                <label for="name" class="font-medium text-gray-900 mb-2">
                                                    {{ $t('Name') }} {{ $t('de_la') }} {{ $t('activity-name') }}
                                                </label>
                                                <input maxlength="50" required v-model="name" type="text" name="name"
                                                    id="name"
                                                    class="bg-gray-100 border-gray-300 focus:ring-orange-400 focus:border-orange-400 rounded-md w-full py-2 px-3 mb-4">
                                            </div>

                                            <div>
                                                <label for="name" class="font-medium text-gray-900 mb-2">
                                                    {{ $t('question.description') }} {{ $t('de_la') }}{{ $t('activity-name')
                                                    }}
                                                </label>
                                                <input maxlength="50" required v-model="description" type="text"
                                                    name="description" id="name"
                                                    class="bg-gray-100 border-gray-300 focus:ring-orange-400 focus:border-orange-400 rounded-md w-full py-2 px-3 mb-4">
                                            </div>
                                            <div>
                                                <label for="start_date" class="font-medium text-gray-900 mb-2">
                                                    {{ $t('start_date') }}
                                                </label>
                                                <input required v-model="start_date" type="date" name="start_date"
                                                    id="start_date"
                                                    class="bg-gray-100 border-gray-300 focus:ring-orange-400 focus:border-orange-400 rounded-md w-full py-2 px-3 mb-4">
                                            </div>

                                            <div>
                                                <label for="end_date" class="font-medium text-gray-900 mb-2">
                                                    {{ $t('end_date') }}
                                                </label>
                                                <input required v-model="end_date" type="date" name="end_date" id="end_date"
                                                    class="bg-gray-100 border-gray-300 focus:ring-orange-400 focus:border-orange-400 rounded-md w-full py-2 px-3 mb-4">
                                            </div>
                                            <div>
                                                <label for="user" class="font-medium text-gray-900 mb-2">
                                                    {{ $t('assignable.categories') }}
                                                </label>
                                                <div class="border rounded-lg mb-4 p-4 h-64 overflow-y-auto">
                                                    <div class="user" v-for="(category2, index) in categories2"
                                                        :key="index">
                                                        <label>
                                                            <input class="mr-1" type="radio" name="selectedCategory"
                                                                :value="category2.id" v-model="selectedCategory">
                                                            {{ category2.name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                    <button type="button"
                                        class="bg-green-500 hover:bg-green-700 text-white font-medium py-2 px-4 mr-2 transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110 ml-auto flex items-center w-full justify-center rounded-md text-sm   shadow-sm sm:ml-3 sm:w-auto"
                                        @click="saveActivity()">
                                        <i class="far fa-save mr-2"></i> {{ $t('Create') }}
                                    </button>
                                    <button type="button"
                                        class="bg-gray-300 hover:bg-gray-500 text-black  font-medium py-1 px-2 transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110 ml-auto flex items-center w-full justify-center rounded-md text-sm   shadow-sm sm:ml-3 sm:w-auto"
                                        @click="modal_crear_activitat = false" ref="cancelButtonRef">
                                        <i class="fas fa-times mr-2"></i>
                                        {{ $t('Cancel') }}
                                    </button>
                                </div>
                            </form>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
    <!--FIN MODAL-->

    <!--MODAL CREAR CATEGORIA-->
    <TransitionRoot as="template" :show="modal_crear_categoria">
        <Dialog as="div" class="relative z-10" @close="modal_crear_categoria = false">
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
                            <form action="" id="category-form" method="POST">
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="flex items-center mx-1 my-1">
                                        <div
                                            class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                            <AcademicCapIcon class="h-6 w-6 text-orange-400" aria-hidden="true" />
                                        </div>
                                        <DialogTitle class="ml-2 flex text-base font-semibold leading-6 text-gray-900">
                                            {{ $t('Create') }} {{ $t('new.2') }} {{ $t('category-name') }}
                                        </DialogTitle>
                                    </div>
                                    <div class="sm:items-start">
                                        <div class="mt-3 text-center sm:mt-0 sm:mx-1 sm:text-left">
                                            <div hidden>
                                                @csrf
                                            </div>
                                            <div>
                                                <label for="name" class="font-medium text-gray-900 mb-2">
                                                    {{ $t('Name') }} {{ $t('Of') }} {{ $t('category-name') }}
                                                </label>
                                                <input maxlength="50" required v-model="name" type="text" name="name"
                                                    id="name"
                                                    class="bg-gray-100 border-gray-300 focus:ring-orange-400 focus:border-orange-400 rounded-md w-full py-2 px-3 mb-4">
                                            </div>
                                            <div>
                                                <label for="user" class="font-medium text-gray-900 mb-2">
                                                    {{ $t('assignable.courses') }}
                                                </label>
                                                <div class="border rounded-lg mb-4 p-4 h-64 overflow-y-auto">
                                                    <div class="user" v-for="(course, index) in courses" :key="index">
                                                        <label>
                                                            <input class="mr-1" type="radio" name="selectedCourse"
                                                                :value="course.id" v-model="selectedCourse">
                                                            {{ course.name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                    <button type="button"
                                        class="bg-green-500 hover:bg-green-700 text-white font-medium py-2 px-4 mr-2 transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110 ml-auto flex items-center w-full justify-center rounded-md text-sm   shadow-sm sm:ml-3 sm:w-auto"
                                        @click="saveCategory()">
                                        <i class="far fa-save mr-2"></i> {{ $t('Create') }}
                                    </button>
                                    <button type="button"
                                        class="bg-gray-300 hover:bg-gray-500 text-black  font-medium py-1 px-2 transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110 ml-auto flex items-center w-full justify-center rounded-md text-sm   shadow-sm sm:ml-3 sm:w-auto"
                                        @click="modal_crear_categoria = false" ref="cancelButtonRef">
                                        <i class="fas fa-times mr-2"></i>
                                        {{ $t('Cancel') }}
                                    </button>
                                </div>
                            </form>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
    <!--FIN MODAL-->


    <!--//MODAL EDITAR CATEGORIA    -->
    <TransitionRoot as="template" :show="modal_editar_categoria">
        <Dialog as="div" class="relative z-10" @close="modal_editar_categoria = false">
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
                            <form action="" id="course-edit" method="POST" v-on:submit.prevent="updateCategory()">

                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="flex items-center mx-1 my-1">
                                        <div
                                            class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                            <AcademicCapIcon class="h-6 w-6 text-orange-400" aria-hidden="true" />
                                        </div>
                                        <DialogTitle class="ml-2 flex text-base font-semibold leading-6 text-gray-900">
                                            {{ $t('Edit') }} {{ $t('category-name') }}
                                        </DialogTitle>
                                    </div>
                                    <div class=" sm:items-start">
                                        <div class="mt-3 text-center sm:mt-0 sm:mx-1 sm:text-left">
                                            <div hidden>
                                                @csrf
                                            </div>
                                            <div>
                                                <label for="name_edit" class="font-medium text-gray-900 mb-2">
                                                    {{ $t('Name') }} {{ $t('Of') }} {{ $t('category-name') }} </label>
                                                <input maxlength="50" v-model="selectedCategory" type="text"
                                                    name="category-edits" id="name_edit"
                                                    class="bg-gray-100 border-gray-300 focus:ring-orange-400 focus:border-orange-400 rounded-md w-full py-2 px-3 mb-4">
                                            </div>

                                            <div>
                                                <label for="user" class="font-medium text-gray-900 mb-2">
                                                    {{ $t('assignable.courses') }}
                                                </label>
                                                <div class="border rounded-lg mb-4 p-4 h-64 overflow-y-auto">
                                                    <div class="user" v-for="(course, index) in courses" :key="index">
                                                        <label>
                                                            <input class="mr-1" type="radio" :value="course.id"
                                                                v-model="selectedCourse">
                                                            {{ course.name }}
                                                        </label>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                    <button type="submit"
                                        class="bg-orange-400 hover:bg-orange-600 font-medium py-2 px-2 mr-2 transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110 ml-auto flex items-center w-full justify-center rounded-md text-sm   shadow-sm sm:ml-3 sm:w-auto">
                                        <i class="far fa-save mr-2"></i> {{ $t('Save') }}
                                    </button>
                                    <button type="button"
                                        class="bg-gray-300 hover:bg-gray-500 text-black  font-medium py-1 px-2 transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110 ml-auto flex items-center w-full justify-center rounded-md text-sm   shadow-sm sm:ml-3 sm:w-auto"
                                        @click="closeModalEdit()" ref="cancelButtonRef">
                                        <i class="fas fa-times mr-2"></i>
                                        {{ $t('Cancel') }}
                                    </button>
                                </div>
                            </form>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>

    <!--MODAL ELIMINAR CATEGORIA-->
    <TransitionRoot as="template" :show="modal_eliminar_categoria">
        <Dialog as="div" class="relative z-10" @close="modal_eliminar_categoria = false">
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
                                    <div class="flex items-center mx-1 my-1">
                                        <div
                                            class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                            <AcademicCapIcon class="h-6 w-6 text-orange-400" aria-hidden="true" />
                                        </div>
                                        <DialogTitle class="ml-2 flex text-base font-semibold leading-6 text-gray-900">
                                            {{ $t('delete') }} {{ $t('category-name') }}
                                        </DialogTitle>
                                    </div>
                                    <div class=" sm:items-start">
                                        <div class="mt-3 text-center sm:mt-0 sm:mx-1 sm:text-left">
                                            <div hidden>
                                                @csrf
                                            </div>
                                            <div>
                                                <label for="name_delete" class="font-medium text-gray-900 mb-2">
                                                    {{ $t('phrase.deleteCategory') }} </label>
                                                <input maxlength="50" v-model="selectedCategory" type="text"
                                                    name="category-edits" id="name_delete"
                                                    class="bg-gray-100 border-gray-300 focus:ring-orange-400 focus:border-orange-400 rounded-md w-full py-2 px-3 mb-4"
                                                    disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                    <button type="submit"
                                        class="ml-2 bg-red-600 hover:bg-red-800 text-white font-medium py-1 px-2 rounded-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110 mr-6"
                                        @click="deleteCategory()">
                                        <i class="far fa-trash-alt mr-2"></i>{{ $t('delete')
                                        }}</button>
                                    <button type="button"
                                        class="bg-gray-300 hover:bg-gray-500 text-black  font-medium py-1 px-2 transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110 ml-auto flex items-center w-full justify-center rounded-md text-sm shadow-sm sm:ml-3 sm:w-auto"
                                        @click="modal_eliminar_categoria = false" ref="cancelButtonRef">
                                        <i class="fas fa-times mr-2"></i>
                                        {{ $t('Cancel') }}
                                    </button>
                                </div>
                            </form>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
    <!--FIN MODAL-->
</template>

<script>
import axios from 'axios';
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import {
    AcademicCapIcon,
    PlusIcon,
    TrashIcon,
    PencilSquareIcon,
    ArrowLeftCircleIcon,
    ArrowRightCircleIcon
} from '@heroicons/vue/24/outline'

export default {
    components: {
        AcademicCapIcon,
        PlusIcon,
        TrashIcon,
        PencilSquareIcon,
        ArrowLeftCircleIcon,
        ArrowRightCircleIcon, Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot
    },
    data() {
        return {
            categories: [],
            categories2: [],
            courses: [],
            modal_crear_categoria: false,
            modal_editar_categoria: false,
            modal_eliminar_categoria: false,
            modal_crear_activitat: false,
            selectedCategory: '',
            selectedCourse: '',
            searchTerm: '',
            pagination: {},
            page: 1,
            perPage: 3, // cantidad de elementos por página
            currentPage: 1 // página actual

        }
    },
    mounted() {
        this.loadCategories(this.currentPage);
    },

    methods: {
        loadCategories(page = 1) {
            axios
                .get(`/categories/index_data?page=${page}`)
                .then((response) => {
                    this.categories = response.data.data;
                    this.currentPage = page;
                })
                .catch((error) => {
                    console.log(error);
                });
            axios
                .get('/allCourses')
                .then((response) => {
                    this.courses = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
            axios
                .get('/allCategories')
                .then((response) => {
                    this.categories2 = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        changePage(page) {
            this.loadCategories(page);
        },

        saveCategory() {
            axios.post('/CreateCategory', {
                name: this.name,
                selectedCourse: this.selectedCourse
            })
                .then(async response => {

                    this.name = '';
                    this.modal_crear_categoria = false;
                    this.selectedCourse = '';
                    this.loadCategories();
                })
                .catch(error => {

                    console.log(error.response)
                })


        },

        saveActivity() {
            axios.post('/CreateActivity', {
                name: this.name,
                description: this.description,
                start_date: this.start_date,
                end_date: this.end_date,
                selectedCategory: this.selectedCategory
            })
                .then(async response => {

                    this.name = '';
                    this.description = '';
                    this.start_date = '';
                    this.end_date = '';
                    this.modal_crear_activitat = false;
                    this.selectedCategory = '';
                })
                .catch(error => {

                    console.log(error.response)
                })


        },

        ModalEditCategory(category) {
            this.modal_editar_categoria = true;
            this.selectedCategory = category.name;
            this.selectedCourse = category.course_id;
            this.id = category.id;

        },

        closeModalEdit() {
            this.modal_editar_categoria = false;
            this.selectedCategory = '';
            this.selectedCourse = null;
            this.id = null;
        },

        updateCategory() {
            axios.put('/category/' + this.id, {
                name_edit: this.selectedCategory,
                selectedCourse: this.selectedCourse
            })
                .then(response => {
                    this.id = '';
                    this.selectedCategory = '';
                    this.selectedCourse = response.data.courses;
                    this.modal_editar_categoria = false;
                })
                .catch(error => {
                    console.log(error.response)
                })

        },

        ModalEliminarCategoria(category) {
            this.modal_eliminar_categoria = true;
            this.id = category.id;
            this.selectedCategory = category.name;
        },

        deleteCategory() {
            axios.get('/category/' + this.id + '/delete', {})
                .then(async response => {
                    this.id = '';
                    this.selectedCategory = '';
                    this.categories = response.data.categories;
                    this.modal_eliminar_categoria = false;
                })
                .catch(error => {
                    console.log(error.response)
                })
        },

        redirectToCourses() {
            window.location.href = "/course";
        },
        redirectToRescources() {
            window.location.href = "/rescources/admin";
        }
    },

    computed: {
        filteredCategories() {
            const searchTermLower = this.searchTerm.toLowerCase();
            return this.categories.filter(category => {
                return category.name.toLowerCase().includes(searchTermLower);
            });
        }
    },
}


</script>
