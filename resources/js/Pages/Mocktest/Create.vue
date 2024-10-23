<template>
    <Layout>
        <Head title="Create Mocktest" />
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="float-start mb-0">Exam Settings</h2>
                    </div>
                </div>
            </div>
        </div>
        <section class="mocktest-create-form">
            <form class="form form-vertical" @submit.prevent="createNewMocktest">
                <div class="row match-height">
                    <div class="col-md-6">
                        <div class="card bg-white">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <Text v-model="createForm.name" label="Exam Title" placeholder="Exam title" />
                                    </div>
<!--                                    <div class="col-md-6">
                                        <label>Select Exam Type</label>
                                        <select v-model="examType" class="form-control">
                                            <option value="main">Live Exam</option>
                                            <option value="practice">Practice Exam</option>
                                        </select>
                                    </div>-->


                                    <div class="row m-0 p-0">
                                        <div class="col-md">
                                            <Text v-model="createForm.pass_mark" label="Pass Mark" placeholder="Pass mark" />
                                        </div>


                                        <div class="col-md">
                                            <Text v-model="createForm.duration" type="number" label="Total Duration"
                                                  placeholder="Total Duration" suffix="Minutes" />
                                        </div>

                                        <div class="col-md">
                                            <Text v-model="createForm.minus_mark" type="number" label="Minus Marking"
                                                  placeholder="e.g 25" suffix="%" />
                                        </div>

                                    </div>
                                    <div class="row mb-1 m-0 p-0 p-0">
                                        <div class="col-md" v-if="examType === 'main'">
                                            <label>Exam Start</label>
                                            <datepicker v-model="createForm.start_time"
                                                        :minimumView="'time'"
                                                        inputFormat="yyyy-MM-dd HH:mm"
                                                        class="form-control"
                                                        placeholder="Start Time"/>
                                        </div>
                                        <div class="col-md" v-if="examType === 'main'">
                                            <label>Exam End</label>
                                            <datepicker v-model="createForm.end_time"
                                                        :minimumView="'time'"
                                                        inputFormat="yyyy-MM-dd HH:mm"
                                                        class="form-control"
                                                        placeholder="End Time"/>
                                        </div>
                                    </div>


                                    <div class="col" v-if="mockType === 'questions'">
                                        <div class="mb-1">
                                            <label>Search question by category</label>
                                            <v-select v-model="searchCateory"
                                                      label="name"
                                                      :options="categories"
                                                      multiple :reduce="cat => cat.id"
                                                      placeholder="Please select a category for question sorting"></v-select>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-3 align-items-center">
                                    <button type="submit" :disabled="createForm.processing" class="btn btn-primary me-1 waves-effect waves-float waves-light">Submit</button>
                                    <h4 class="fw-bold" v-if="mockType === 'questions'">Select Questions: <span class="badge bg-primary text-white">{{ makeMockQuestions?.length }}</span></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-white">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="about">About</label>
                                    <textarea id="about" v-model="createForm.about" class="form-control"
                                              rows="9"
                                              placeholder="e.g Explain about this mock test"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </section>



        <section v-if="mockType === 'questions'" class="app-user-list">
            <!-- list and filter start -->
            <div class="card">
                <div class="card-datatable table-responsive pt-0">
                    <div class="d-flex justify-content-between align-items-center header-actions mx-0 row mt-75">
                        <div class="col-sm-12 col-lg-4 d-flex justify-content-center justify-content-lg-start">
                            <div class="select-search-area">
                                <label>Show <select class="form-select" v-model="perPage">
                                    <option :value="undefined">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select> entries</label>
                            </div>
                        </div>

                        <div class="col-sm-12 col-lg-8 ps-xl-75 ps-0">
                            <div
                                class="d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap">
                                <div class="select-search-area">
                                    <label>Search:<input v-model="search" type="search" class="form-control"
                                                         placeholder="Type here for search"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="user-list-table table table-striped">
                        <thead class="table-light">
                        <tr class="">
                            <th class="sorting">Title</th>
                            <th class="sorting">Category</th>
                            <th class="sorting">Answer</th>
                            <th class="sorting">Mark</th>
                            <th class="sorting">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="question in questions.data" :key="question.id">
                            <td><span v-html="question.title"></span></td>
                            <td>{{ question.category?.name }}</td>
                            <td class="text-capitalize">{{ question?.answer }}</td>
                            <td class="text-capitalize">{{ question?.mark }}</td>
                            <td>
                                <div class="demo-inline-spacing">
                                    <button @click="addToMake(question.id)"
                                            class="btn btn-icon btn-icon rounded-circle  waves-effect waves-float waves-light"
                                            :class="makeMockQuestions?.includes(question.id) ? 'bg-primary text-white' : 'bg-light-primary text-primary'"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                            <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>


                    <div class="d-flex justify-content-between mx-0 row pt-1 align-items-center">
                        <div class="col-sm-12 col-md-6">
                            <div class="text-shadow">
                                Showing {{ questions?.from }} to {{ questions?.to }} of {{ questions?.total }} entries</div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <ul class="pagination flex justify-content-end">
                                <li
                                    class="paginate_button page-item"
                                    v-for="link in questions?.links"
                                    :class="{ 'active' : link.active }"
                                >
                                    <button @click="changeCurrentPage(link.label)" class="page-link" v-html="link.label" />
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- list and filter end -->
        </section>
    </Layout>
</template>

<script setup>
import Text from '@/Components/form/Text.vue';
import {computed, onMounted, ref, watch} from 'vue';
import axios from 'axios'
import Layout from "@/Shared/Layout.vue";
import {router, useForm} from "@inertiajs/vue3";
import debounce from "lodash/debounce.js";
import Datepicker from "vue3-datepicker";

let props = defineProps({
    categories: Object,
    url: String,
    filters: Object,
    subqbycat_url: String,
})

const mockType = ref('questions')
const examType = ref('main')
let createForm = useForm({
    name: null,
    total_q: null,
    duration: null,
    categories: [],
    pass_mark:null,
    start_time:null,
    end_time:null,
    pass_parsentage:null,
    minus_mark:null,
    password:null,
    perPage:null,
    about:null,
    examType:null,
})

const makeMockQuestions = ref([])

let createNewMocktest = () => {
    if(mockType.value === 'questions'){
        createForm.total_q = makeMockQuestions.value?.length
        createForm.examType = examType.value
    }
    router.post(props.url, {...createForm, checkedQuestions: makeMockQuestions.value, question_type:mockType.value},{
        onSuccess: () => {
            createForm.reset()
        }
    })
}




const categorySelected = (e) => {
    axios.post(props.subqbycat_url, { category: e })
        .then(res => {
            createForm.categories = res.data
        })
        .catch(error => {
            console.log(error)
        });
}

const totalTakenQuestion = computed(() => {
    let count = 0;
    createForm.categories.forEach((category) => {
        count += category.take
        console.log(count);
    })
    return count
})

const addToMake = (id) => {
    if(makeMockQuestions.value?.includes(id)){
        const index = makeMockQuestions.value?.indexOf(id);
        if (index > -1) {
            makeMockQuestions.value?.splice(index, 1);
        }
    }else{
        makeMockQuestions.value.push(id)
    }
}




const questions = ref([])
let searchCateory = ref(null)
let search = ref(props.filters?.search);
let perPage = ref(props.filters?.perPage);
let currentPage = ref(1)

const changeCurrentPage = (page) => {
    if(page === 'Next &raquo;'){
        if (currentPage.value < questions.value.last_page){
            currentPage.value = currentPage.value + 1;
        }
    }
    else if(page === '&laquo; Previous'){
        if(currentPage.value > 0){
            currentPage.value = currentPage.value - 1;
        }
    }else{
        currentPage.value = page
    }

}

const getAllQuestions = () =>{
    axios.get('/panel/questions-for-mock-create').then((res) =>{
        questions.value = res?.data;
    }).catch((err) => console.log(err))
}

watch([search, perPage, searchCateory, currentPage], debounce(function ([val, val2, val3, page]) {
    console.log(val3)
    axios.get('/panel/questions-for-mock-create', {
        params:{
            search: val,
            perPage: val2,
            categories: val3,
            page:page
        }
    }).then((res) =>{
        questions.value = res?.data;
    }).catch((err) => console.log(err))
}, 300), {deep:true});


onMounted(()=>{
    getAllQuestions();
})

</script>
