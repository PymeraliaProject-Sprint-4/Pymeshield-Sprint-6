<template>
    <div>
        <h1 class="p-3 text-center font-medium">{{ $t('task.task-list-title') }}</h1>
        <div class="mb-1">
            <div class="flex justify-end m-2">
                <div class="mb-1 xl:w-96">
                    <div class="input-group relative flex flex-wrap items-stretch w-full mb-4">
                        <input type="text" id="buscador" name="buscador"
                            class="mr-1 form-control relative flex-auto min-w-0 block px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition  m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                            :placeholder="$t('task.search_task')" aria-label="Search" autocomplete="off"
                            v-model="buscado /* ruteado a la variable buscado de data() */"
                            @keyup="search /* llama al método cuando acabas de pulsar la tecla */" @keydown="keydown"
                            v-on:keypress="isLetter($event)" @copy.prevent @paste.prevent>
                        <label for="buscador" class="text-red-500">{{ labelinpt }}</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="table-auto w-full shadow-lg rounded-lg">
                <thead>
                    <tr class="bg-orange-400 text-white">
                        <th class="px-6 py-3 uppercase">
                            {{ $t("task.survey.answer") }}
                        </th>
                        <th class="px-6 py-3 uppercase">
                            {{ $t("task.recommendation") }}
                        </th>
                        <th class="px-6 py-3 uppercase">
                            {{ $t("task.danger") }}
                        </th>
                        <th class="px-6 py-3 uppercase">
                            {{ $t("task.managed") }}
                        </th>
                        <th class="px-6 py-3 uppercase">
                            {{ $t("task.price") }}
                        </th>
                        <th class="px-6 py-3 uppercase"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-orange-50 hover:bg-orange-100 text-center" v-for="task in tasks" :key="task.id">
                        <td class="px-6 py-4">
                            {{ task.name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ task.recommendation }}
                        </td>
                        <td class="px-6 py-4">
                            {{ task.peligro }}
                        </td>
                        <td class="px-6 py-4">
                            <RadioGroupComponent :default="task.manages"
                                @selectedValue="(value) => editTask(value, task.id)" />
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                {{ task.manages == 'No aceptada' || task.manages == 'Me lo gestiono yo' ? '' : task.price }}
                            </div>
                            <div>
                                <input type="text" step="0.01" v-if="task.manages == 'Me lo gestiono yo'"
                                    v-bind:value="task.price_customer == '0' ? '' : task.price_customer /* ruteado a la variable buscado de data() */"
                                    @keyup="addPrice($event, task.id) /* llama al método cuando acabas de pulsar la tecla */"
                                    class="peer block min-h-[auto] w-20 text-center rounded border-0 bg-transparent py-[0.32rem] px-3 leading-[1.6] outline-none transition-all duration-200 ease-linear text-gray-900 dark:text-black"
                                    placeholder="Añade el precio" @keydown="keydown" v-on:keypress="isNUMBER($event)"
                                    @copy.prevent @paste.prevent :disabled="final_budget" />
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            €
                        </td>
                    </tr>
                    <tr v-show="swtotalcol" class="bg-orange-200 text-center font-bold">
                        <td class="px-6 py-4">{{ $t("total") }}</td>
                        <td class="px-6 py-4"></td>
                        <td class="px-6 py-4"></td>
                        <td class="px-6 py-4"></td>
                        <td class="px-6 py-4">{{ totals }}
                        </td>
                        <td class="px-6 py-4">€</td>
                    </tr>
                </tbody>
            </table>
            <div v-show="errsearchcol" class="bg-orange-200 text-center">
                <div class="flex justify-center px-6 py-4">
                    NO SE HAN ENCONTRADO RESULTADOS
                </div>
            </div>
        </div>
        <div class="pt-4 flex w-full justify-between">
            <nav class="isolate inline-flex Fspace-x-px rounded-md shadow-sm" aria-label="Pagination">
                <TailwindPagination :data="taskdata" @pagination-change-page="onPageChanged"
                    :current-page="taskdata.current_page" />
            </nav>
            <div>
                <button
                    class="bg-orange-400 hover:bg-orange-600 font-medium py-1 px-2 mr-4 rounded-lg  transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110 ml-auto block flex items-center"
                    @click.prevent="finalBudget()">
                    <i class="far fa-save mr-2"></i>{{ $t("task.finish_budget") }}
                </button>
            </div>
        </div><!-- Paginación -->
    </div>
</template>

<script>
import RadioGroupComponent from '../components/Radio.vue'
import { ref } from 'vue'
import axios from 'axios';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import { TailwindPagination } from 'laravel-vue-pagination';

export default {
    components: {
        RadioGroupComponent,
        TailwindPagination,
    },
    beforeMount() {
        this.getTasks()
        this.getTotal()

    },
    mounted: function () {
    },
    data() {
        return {
            url: 'all-data',
            buscado: '',
            budget: null,
            totals: '',
            tasks: [],
            taskdata: {},
            users: [],
            questionnaires: [],
            dataTask: { manages: '' },
            idTask: '',
            title: '',
            searchinpt: [],
            labelinpt: '',
            swtotalcol: true,
            errsearchcol: false,
            setTimeoutPrice: null,
            final_budget: false,
            priceinpt: false,
            pricecol: false,
        }
    },
    methods: {
        keydown: function (e) {
            console.log(e)
        },

        isLetter(e) {
            let char = String.fromCharCode(e.keyCode); // Get the character
            if (/^[A-Za-z0-9?¿ ]+$/.test(char)) return true; // Match with regex
            else e.preventDefault(); // If not match, don't add to input text
        },

        isNUMBER(e) {
            let char = String.fromCharCode(e.keyCode); // Get the character
            if (/^[0-9,.]+$/.test(char)) return true; // Match with regex
            else e.preventDefault(); // If not match, don't add to input text
        },
        getTotal() {
            this.$axios.get('get-total/' + this.budget).then(res => {
                this.totals = res.data;
                return
            });
            this.totals = 0
        },

        finalBudget() {
            this.final_budget = true;
            Swal.fire({
                title: 'Enhorabuena!',
                text: 'Presupuesto finalizado!',
                icon: 'success',
                didClose: function () {
                    history.back();
                }
            });
        },

        showAll() {
            this.labelinpt = '';
            this.swtotalcol = true
            this.errsearchcol = false
            this.buscado = null;
            this.url = 'all-data';
            this.getTasks()
        },

        search() {
            if (!this.buscado) return this.showAll()
            this.$axios.get('buscador-budgetTaks/' + this.buscado).then(res => {
                this.url = 'buscador-budgetTaks/' + this.buscado;
                this.searchinpt = res.data
                if (this.searchinpt == '') {
                    // this.url = 'all-data';
                    // this.getTasks()
                    this.swtotalcol = false
                    this.errsearchcol = true
                    this.labelinpt = "No has introducido los datos correctos!";
                } else {
                    this.labelinpt = '';
                    this.swtotalcol = true
                    this.errsearchcol = false
                }
                this.getTasks();
            }).catch(function (error) {
                /*
                Swal.fire({
                    title: 'Oh no!',
                    text: 'Algo no funciona!',
                    icon: 'danger',
                });
                */
            });
        },

        addPrice(event, key) {
            if (this.setTimeoutPrice) clearTimeout(this.setTimeoutPrice)
            let price = event.target.value.replaceAll(',', '.');
            let id = key;
            if (!price) price = 0;
            this.setTimeoutPrice = setTimeout(() => {
                this.$axios.put('add-price/' + id + '/' + price).then(res => {
                    this.getTotal()
                    this.getTasks();
                }).catch(function (error) {
                    /*
                    Swal.fire({
                    title: 'Oh no!',
                    text: 'Algo no funciona!',
                    icon: 'danger',
                });
                    */
                })
            }
                , 900
            )
        },

        //Aquesta funció recupera les dades de les tasques fent una sol·licitud GET a l'URL 'tasks-data' mitjançant la biblioteca axios.
        //En cas de resposta correcta, actualitza l'estat de "tasques" del component amb les dades retornades de l'API.
        getTasks(page = 1) {
            let budget = window.location.pathname.split('/').pop();
            if (this.url == 'buscador-budgetTaks/' + this.buscado) {
                this.$axios.get(this.url, {
                    params: {
                        budget
                    },
                }).then(res => {
                    this.tasks = res.data
                    this.tasks.forEach(task => {
                        if (task.manages == 'Me lo gestiono yo') {
                            this.pricecol = true
                            return
                        }
                    });

                });
            } else {
                let id = 5;
                this.budget = window.location.pathname.split('/').pop();
                this.$axios.get(this.url + '/' + this.budget, {
                    params: {
                        id,
                        page
                    },
                }).then(res => {
                    this.tasks = res.data.data
                    this.taskdata = res.data
                    this.tasks.forEach(task => {
                        if (task.manages == 'Me lo gestiono yo') {
                            this.pricecol = true
                            return
                        }
                    });
                });
            }
        },

        onPageChanged(page) {
            this.getTasks(page);
        },
        //La función `editTask` hace una petición `PUT` utilizando la librería axios para actualizar una tarea en el servidor, pasando el identificador de la tarea `this.idTask` y los nuevos
        //datos de la tarea `this.dataTask`. Si la petición es exitosa, se ejecutan las siguientes acciones:

        //1.  Se asigna un valor vacío a `this.idTask`
        //2.  Se invoca la función `getTasks` para actualizar la lista de tareas
        //3.  Se oculta el modal que contiene el formulario de edición
        //4.  Se muestra un mensaje de éxito "La tarea ha sido editada" utilizando la librería swal.

        //En caso contrario (capcatch), se muestra un mensaje de error "Algo no funciona".
        editTask(value, id) {
            this.idTask = id
            this.dataTask = { manages: value }
            this.$axios.put('edit-accepted/' + this.idTask, this.dataTask).then(res => {
                this.idTask = ''
                this.getTotal()
                this.getTasks()
                /*
                Swal.fire({
                    title: 'Editada!',
                    text: 'La tarea ha sido editada!',
                    icon: 'success',
                });
                */
                if (value == 'Me lo gestiono yo') {
                    this.pricecol = true
                } else {
                    this.pricecol = false
                }
            }).catch(function (error) {
                Swal.fire({
                    title: 'Oh no!',
                    text: 'Algo no funciona!',
                    icon: 'danger',
                });
            });
        },
    }
}
</script>
