<template>

    <Layout>

    <Head title="Student List"/>
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">Student List</h2>
                </div>
            </div>
        </div>
        <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
            <div class="mb-1 breadcrumb-right">
                <button class="dt-button add-new btn btn-primary" tabindex="0" type="button" data-bs-toggle="modal"
                        data-bs-target="#createNewStudent"><span>Add New Student</span></button>
            </div>
        </div>
    </div>
    <section class="app-user-list">
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
                                <label>Search:<input v-model="search" type="search" class="form-control" placeholder=""
                                                     aria-controls="DataTables_Table_0"></label>
                            </div>
                        </div>
                    </div>
                </div>

                <table class="user-list-table table">
                    <thead class="table-light">
                    <tr class="">
                        <th class="sorting">Name</th>
                        <th class="sorting">Phone</th>
                        <th class="sorting">Active on</th>
                        <th class="sorting">Active Status</th>
                        <th class="sorting">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="student in students.data" :key="student.id">
                        <td>
                            <div class="d-flex justify-content-left align-items-center">
                                <div class="avatar-wrapper">
                                    <div class="avatar  me-1">
                                        <img :src="`https://ui-avatars.com/api/?background=random&color=fff&name=${student.name}`"
                                             :alt="student.name" height="32" width="32">
                                    </div>
                                </div>
                                <div class="d-flex flex-column">
                                    <div class="student_name text-truncate text-body">
                                        <span class="fw-bolder">{{ student.name }}</span>
                                    </div>
                                    <small class="emp_post text-muted">{{ student.email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ student.phone }}</td>
                        <td>{{ student.active_on }}</td>
                        <td>
                            <span class="badge bg-light-primary" v-if="student.is_active">Activated</span>
                            <span class="badge bg-light-warning" v-else>Deactivated</span>
                        </td>
                        <td>


                            <div class="btn-group dropup dropdown-icon-wrapper">

                                <button type="button"
                                        class="btn dropdown-toggle dropdown-toggle-split waves-effect waves-float waves-light"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                </button>

                                <div class="dropdown-menu">
                                      <span class="dropdown-item"
                                            v-if="student.is_active == 1"
                                            @click="avticationStatus(student.id, false)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down-left"><line x1="17" y1="7" x2="7" y2="17"></line><polyline points="17 17 7 17 7 7"></polyline></svg>
                                        <span class="ms-1">Inactive</span>
                                    </span>
                                    <span class="dropdown-item"
                                          v-else
                                          @click="avticationStatus(student.id, true)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-up-right"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                                        <span class="ms-1">Active</span>
                                    </span>


                                    <Link class="dropdown-item" :href="student.show_url">
                                        <Icon title="eye"/>
                                       <span class="ms-1">Show</span>
                                    </Link>

                                    <Link class="dropdown-item" :href="`/panel/groups?user_id=${student.id}`">
                                        <vue-feather type="users"/>
                                        <span class="ms-1">Group</span>
                                    </Link>
                                    <span class="dropdown-item"
                                          @click="deleteItemModal(student.delete_url)">
                                        <Icon title="trash"/>
                                       <span class="ms-1">Delete</span>
                                    </span>
                                </div>
                            </div>

<!--
                            <div class="demo-inline-spacing">

                                <button type="button"
                                        @click="editItem(student.show_url)"
                                        class="btn btn-icon btn-icon rounded-circle bg-light-primary waves-effect waves-float waves-light">
                                    <Icon title="pencil" />
                                </button>
                                <button type="button"
                                        @click="showItem(student.show_url)"
                                        class="btn btn-icon btn-icon rounded-circle bg-light-warning waves-effect waves-float waves-light">
                                    <Icon title="eye" />
                                </button>
                                <button @click="deleteItemModal(student.delete_url)" type="button"
                                        class="btn btn-icon btn-icon rounded-circle waves-effect waves-float waves-light bg-light-danger">
                                    <Icon title="trash"/>
                                </button>
                                <button @click="deactiveStudent(student.deactivate_student)" type="button"
                                        class="btn btn-icon btn-icon rounded-circle waves-effect waves-float waves-light bg-light-danger">
                                    <Icon title="trash"/>
                                </button>
                            </div>-->
                        </td>
                    </tr>
                    </tbody>
                </table>
                <Pagination :links="students.links" :from="students.from" :to="students.to" :total="students.total"/>
            </div>
        </div>
        <!-- list and filter end -->
    </section>

    <!-- Modal to add new Student starts-->
    <Modal id="createNewStudent" title="Create New User" v-vb-is:modal>
        <form @submit.prevent="createNewStudent">
            <div class="modal-body">
                <Text v-model="createForm.name" type="text" label="Name" placeholder="Name"
                      :error="props.errors.name"/>
                <Text v-model="createForm.phone" type="text" label="Phone" placeholder="Phone number"
                      :error="props.errors.phone"/>
                <Text v-model="createForm.email" type="email" label="Email" placeholder="Email Address"
                      :error="props.errors.email"/>
                <Password v-model="createForm.password" label="Password" :error="props.errors.password"/>
                <Image v-model="createForm.photo" label="Photo" :error="props.errors.photo"/>
                <!--                        <Image v-model="createForm.certificate" label="Certificate" :error="props.errors.certificate"/>-->
            </div>
            <div class="modal-footer">
                <button :disabled="createForm.processing" type="submit"
                        class="btn btn-primary waves-effect waves-float waves-light">Submit
                </button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                        aria-label="Close">Cancel
                </button>
            </div>
        </form>
    </Modal>
    <!-- Modal to add new Student Ends-->
    <Modal id="showItem" title="Show Student" v-vb-is:modal size="sm">
        <div class="modal-body">
            <div class="d-flex align-items-start gap-3">
                <div class="profile-image-wrapper">
                    <div class="profile-image">
                        <div class="avatar">
                            <img :src="showData.photo"  width="75" height="75"  alt="Profile Picture">
                        </div>
                    </div>
                </div>
                <div>
                    <h3>{{ showData.name }}</h3>
                    <h5>{{ showData.email}}</h5>
                    <h6>{{ showData.phone }}</h6>
                    <span class="badge badge-light-primary profile-badge">{{ showData.role }}</span>
                </div>
            </div>

            <hr class="mb-2"/>

            <h4>Enroll Courses</h4>
            <ul>
                <li v-for="data in showData?.courses">{{ data?.course?.name }}</li>
            </ul>
        </div>
    </Modal>

    <Modal id="editItem" title="Edit User" v-vb-is:modal>
        <form @submit.prevent="updateStudent(showData.id)">
            <div class="modal-body">
                <Text v-model="updateForm.name" type="text" label="Name" placeholder="Name"
                      :error="props.errors.name"/>
                <Text v-model="updateForm.phone" type="text" label="Phone" placeholder="Phone number"
                      :error="props.errors.phone"/>
                <Text v-model="updateForm.email" type="email" label="Email" placeholder="Email Address"
                      :error="props.errors.email"/>
                <Image v-model="updateForm.photo" label="Photo" :error="props.errors.photo"/>
<!--                <Image v-model="updateForm.certificate" label="Certificate" :error="props.errors.certificate"/>-->
            </div>
            <div class="modal-footer">
                <button :disabled="createForm.processing" type="submit"
                        class="btn btn-primary waves-effect waves-float waves-light">Submit
                </button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                        aria-label="Close">Cancel
                </button>
            </div>
        </form>
    </Modal>

    </Layout>

</template>

<script setup>
    import Pagination from '@/Components/Pagination.vue'
    import Modal from '@/Components/Modal.vue'
    import Icon from '@/Components/Icon.vue'
    import Password from '@/Components/form/Password.vue'
    import Text from '@/Components/form/Text.vue'
    import Image from '@/Components/form/Image.vue'
    import {ref, watch} from 'vue';
    import debounce from "lodash/debounce"
    import Swal from 'sweetalert2'
    import axios from "axios";
    import {router, useForm} from "@inertiajs/vue3";
    import Layout from "@/Shared/Layout.vue";
    import moment from "moment/moment.js";

    let props = defineProps({
        students: Object,
        filters: Object,
        url: String,
        main_url:String,
        errors:Object,
    });

    let createForm = useForm({
        name: '',
        email: '',
        phone: '',
        designation: '',
        photo: '',
        password: '',
        certificate: '',
    });
    let updateForm = useForm({
        name: '',
        email: '',
        phone: '',
        designation: '',
        photo: '',
    });


    let createNewStudent = () => {
        router.post(props.url, createForm,{
            onSuccess: () => {
                document.getElementById('createNewStudent').$vb.modal.hide()
            }
        });
    }


    let updateStudent = (id) => {
        alert("called")
        // router.post("panel/student/update/"+id, updateForm, {
        //     onSuccess: ()=>{
        //         document.getElementById('editItem').$vb.modal.hide()
        //         updateForm.reset()
        //     }
        // })
    }


    let deleteItemModal = (url) => {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                router.delete(url, {
                    preserveState: true, replace: true, onSuccess: page => {
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                    },
                    onError: errors => {
                        Swal.fire(
                            'Oops...',
                            'Something went wrong!',
                            'error'
                        )
                    }
                })
            }
        })
    };



    let showData = ref([])
    let showItem = (data) => {
        showData.value =data
        document.getElementById('showItem').$vb.modal.show()
    }

    let editItem = (show_url) => {
        axios.get(show_url).then(res =>{
            showData.value = res.data;

            updateForm.name = res.data.name
            updateForm.email = res.data.email
            updateForm.phone =  res.data.phone
            updateForm.designation = res.data.designation
            updateForm.photo = res.data.photo
            updateForm.certificate = res.data.certificate

            document.getElementById('editItem').$vb.modal.show()
        }).catch(err => {
            console.log(err)
        })
    }



    let avticationStatus = (value, status) => {
        router.post(`/panel/student/activation/${value}?show_status=${status}`, {
            onSuccess: () => {
                Swal.fire(
                    'Deactivated!',
                    'This account has been deactivated.',
                    'success'
                )
            }
        })
    }




    let search = ref(props.filters.search);
    let perPage = ref(props.filters.perPage);

    watch([search, perPage], debounce(function ([val, val2]) {
        router.get('', {search: val, perPage: val2}, {preserveState: true, replace: true});
    }, 300));

</script>
<style>
.showbtn{
    border: none;
    padding: 8px 12px;
    height: 100%;
    color: white;
    border-radius: 8px;
}
</style>
