<template>
    <Layout>

    <div class="row match-height">
        <div class="col-md-7 col-lg-12 col-xl-12 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="d-flex justify-content-between flex-column col-xl-6 col-21">
                            <div class="d-flex justify-content-start">
                                <span class="b-avatar badge-light-danger rounded">
                                    <img class="rounded me-2" style="width: 140px;height: 140px;"
                                         :src="user.photo_url" alt="avatar">
                                </span>
                                <div class="d-flex flex-column ml-1">
                                    <div class="mb-1">
                                        <h4 class="mb-0"> {{ user.name }} </h4>
                                        <span class="card-text">{{ user.email }}</span>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <button class="btn-gradient-info btn me-2" @click="editUserItem(edit_url)">Edit</button>
                                        <button class="btn-gradient-danger btn" @click="deleteItemModal(user?.id)">Delete</button>
<!--                                        <button @click="showCertificate" class="btn btn-gradient-success ms-2">Show</button>-->
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-xl-6 col-12">
                            <table class="mt-2 mt-xl-0 w-100">
                                <tr>
                                    <th class="pb-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14px" height="14px"
                                             viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round"
                                             class="mr-75 feather feather-user">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                        <span class="ms-1 font-weight-bold">Username</span>
                                    </th>
                                    <td class="pb-50"> {{ user.name }}</td>
                                </tr>
                                <tr>
                                    <th class="pb-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14px" height="14px"
                                             viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round"
                                             class="mr-75 feather feather-check">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                        <span class="ms-1 font-weight-bold">Activation Status</span>
                                    </th>
                                    <td class="pb-50 text-capitalize"><span class="badge"
                                                                            :class="user.is_active ? 'bg-success' : 'bg-danger'">{{ user.is_active ? "Active" : "Inactive"}}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pb-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14px" height="14px"
                                             viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round"
                                             class="mr-75 feather feather-star">
                                            <polygon
                                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                        </svg>
                                        <span class="ms-1 font-weight-bold">Role</span>
                                    </th>
                                    <td class="pb-50 text-capitalize"> {{ user.role }}</td>
                                </tr>
                                <tr>
                                    <th>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14px" height="14px"
                                             viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round"
                                             class="mr-75 feather feather-phone">
                                            <path
                                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                        </svg>
                                        <span class="ms-1 font-weight-bold">Contact</span>
                                    </th>
                                    <td>{{ user.phone }}</td>
                                </tr>
                                <tr>
                                    <th class="pb-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14px" height="14px"
                                             viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round"
                                             class="mr-75 feather feather-flag">
                                            <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path>
                                            <line x1="4" y1="22" x2="4" y2="15"></line>
                                        </svg>
                                        <span class="ms-1 font-weight-bold">Joind On</span>
                                    </th>
                                    <td class="pb-50">
                                        {{ date(user.created_at, 'll') }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-3 col-md-12 col-12">
            <div class="card mb-4">
                <div class="card-body text-center p-0 counter_card">
                    <h4 class="mt-3">Enrolled Courses</h4>
                    <p>{{ courses.length }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-12 col-12">

            <div class="card mb-4">
                <div class="card-body text-center p-0 counter_card">
                    <h4 class="mt-3">Joined Classes</h4>
                    <p>{{ zoomes }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-12 col-12">

            <div class="card mb-4">
                <div class="card-body text-center p-0 counter_card">
                    <h4 class="mt-3">Attend Moktests</h4>
                    <p>{{ user.mocktests.length }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-12 col-12">
            <div class="card mb-4">
                <div class="card-body text-center p-0 counter_card">
                    <h4 class="mt-3">Total Payment</h4>
                    <p>{{ user.transactions.length }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">

            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab"
                            data-bs-toggle="tab" data-bs-target="#home"
                            type="button" role="tab" aria-controls="home"
                            aria-selected="true">Course History</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="history-tab"
                            data-bs-toggle="tab" data-bs-target="#history"
                            type="button" role="tab" aria-controls="history"
                            aria-selected="true">Country Details</button>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="col">
                        <div class="card">
                            <div class="card-datatable table-responsive pt-0">
                                <div class="float-end header-actions mx-0 row mt-75 mb-75">
<!--                                    <button class="btn btn-sm btn-primary" tabindex="0" type="button" data-bs-toggle="modal"-->
<!--                                            data-bs-target="#assignNewCourse">Assign New Course</button>-->
                                </div>
                                <table class="user-list-table table">
                                    <thead class="table-light">
                                    <tr class="">
                                        <th class="sorting" width="30%">Courses</th>
                                        <th class="sorting">Price</th>
                                        <th class="sorting">Enrolled</th>
                                        <th class="sorting">Access Duration</th>
                                        <th class="sorting">Show Status</th>
                                        <th class="sorting">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="course in courses" :key="course.id">
                                        <td>{{ course?.course?.name.slice(0, 30)+"....." }}</td>
                                        <td>{{ course.course?.price }}</td>
                                        <td>{{ course?.enroll_start }} / {{ course.enroll_expire }}</td>
                                        <td>{{ course?.duration != null ? course.duration :  ''}} {{ course.duration_type != null ? course.duration_type : ''  }}</td>
                                        <td>
                                <span class="text-success" v-if="course.is_show">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                </span>
                                            <span class="text-danger" v-else>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                </span>
                                        </td>
                                        <td>
                                            <div class="btn-group dropup dropdown-icon-wrapper dropstart">
                                                <button type="button"
                                                        class="btn btn-icon dropdown-toggle dropdown-toggle-split waves-effect waves-float waves-light"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                                </button>

                                                <div class="dropdown-menu">

                                        <span class="dropdown-item"
                                              v-if="course.is_show"
                                              @click="avticationStatus(course?.id, false)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down-left"><line x1="17" y1="7" x2="7" y2="17"></line><polyline points="17 17 7 17 7 7"></polyline></svg>
                                            <span class="ms-1">Inactive</span>
                                        </span>
                                                    <span class="dropdown-item"
                                                          v-else
                                                          @click="avticationStatus(course?.id, true)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-up-right"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                                            <span class="ms-1">Active</span>
                                        </span>




                                                    <Link  :href="`/courses/${course.course?.id}`" class="dropdown-item">
                                                        <Icon title="eye"/>
                                                        <span class="ms-1">Course</span>
                                                    </Link>
                                                    <span class="dropdown-item" @click="editItem(course?.id)">
                                            <Icon title="pencil"/>
                                            <span class="ms-1">Edit</span>
                                        </span>
                                                    <span class="dropdown-item" @click="deleteItemModal(course?.id)">
                                            <Icon title="trash"/>
                                           <span class="ms-1">Delete</span>
                                        </span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <Pagination :links="user.courses.links" :from="user.courses.from" :to="user.courses.to"
                                            :total="user.courses.total"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade show" id="history" role="tabpanel" aria-labelledby="history-tab">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h2>Name : {{ user.name }}</h2>
                                <h4>Phone:{{ user.phone }}</h4>
                                <h4>Full Phone:{{ user.full_phone }}</h4>
                                <h4>Country Code:{{ user.dialCode }}</h4>
                                <h4>Couontry Name:{{ user.countryName }}</h4>
                                <h4>Iso:{{ user.iso2 }}</h4>
                                <h4>Email: {{ user.email }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





    <!-- Modal to add new user starts-->
    <Modal id="assignNewCourse" title="Assign Course" v-vb-is:modal>
        <form @submit.prevent="assignNewCourse">
            <div class="modal-body">
                <label>Main Category: </label>
                <div class="mb-1">
                    <v-select v-model="createForm.course_id"
                              placeholder="Select Course For Assign"
                              label="name" :options="allCourses"
                              :reduce="course => course.id"></v-select>

                        <label class="mt-1">Access Time:</label>
                        <fieldset>
                            <div class="input-group">
                                <input type="text" v-model="createForm.access_time" class="form-control" placeholder="Access Limitation Time" aria-label="Amount">
                                <select class="form-control"  v-model="createForm.access_type" placeholder="Chose Access Type">
                                    <option selected value="">~~ Chose Option ~~</option>
                                    <option value="Year">Year</option>
                                    <option value="Month">Month</option>
                                    <option value="Days">Days</option>
                                </select>

                            </div>
                        </fieldset>

                    <div class="row mt-1 mb-2">
                        <div class="col-6">
                            <label>Enroll Start: </label>
                            <datepicker v-model="createForm.enroll_start" class="form-control" placeholder="Choose a date" />
                            <span class="error text-danger" v-if="props.errors.active_on">{{ props.errors.active_on }}</span>
                        </div>
                        <div class="col-6">
                            <label>Enroll End: </label>
                            <datepicker v-model="createForm.enroll_end" class="form-control" placeholder="Choose a date" />
                            <span class="error text-danger" v-if="props.errors.active_on">{{ props.errors.active_on }}</span>
                        </div>
                    </div>
                    <div class="col mt-2">
                        <label class="form-check-label mb-50" for="status">Is Show This Course Form Now: </label>
                        <div class="form-check form-switch form-check-success mb-1">
                            <input type="checkbox" class="form-check-input" id="status" v-model="createForm.is_show"
                                   :true-value="true" :false-value="false" />
                            <label class="form-check-label" for="status">
                                    <span class="switch-icon-left">
                                        <Icon title="check" />
                                    </span>
                                <span class="switch-icon-right">
                                        <Icon title="x" />
                                    </span>
                            </label>
                        </div>
                    </div>
                </div>
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
    <!-- Modal to add new user Ends-->

    <!-- Modal to add new user starts-->
    <Modal id="editItem" title="Edit Assign Course" v-vb-is:modal>
        <form @submit.prevent="updateAssignCourse(showData)">
            <div class="modal-body">
                <div class="mb-1">
<!--                    <v-select v-model="updateForm.course_id"-->
<!--                              @update:modelValue="categorySelected"-->
<!--                              placeholder="Select Course For Assign"-->
<!--                              label="name" :options="allCourses" :reduce="course => course.id"></v-select>-->

                        <label class="mt-1">Access Time:</label>
                        <fieldset>
                            <div class="input-group">
                                <input type="text" v-model="updateForm.access_time" class="form-control" placeholder="Access Limitation Time" aria-label="Amount">
                                <select class="form-control"  v-model="updateForm.access_type" placeholder="Chose Access Type">
                                    <option selected value="">~~ Chose Option ~~</option>
                                    <option value="Year">Year</option>
                                    <option value="Month">Month</option>
                                    <option value="Days">Days</option>
                                </select>

                            </div>
                        </fieldset>

                    <div class="row mt-1 mb-2">
                        <div class="col-6">
                            <label>Enroll Start: </label>
                            <datepicker v-model="updateForm.enroll_start" class="form-control" placeholder="Choose a date" />
                            <span class="error text-danger" v-if="props.errors.active_on">{{ props.errors.active_on }}</span>
                        </div>
                        <div class="col-6">
                            <label>Enroll End: </label>
                            <datepicker v-model="updateForm.enroll_end" class="form-control" placeholder="Choose a date" />
                            <span class="error text-danger" v-if="props.errors.active_on">{{ props.errors.active_on }}</span>
                        </div>
                    </div>
                    <div class="col mt-2">
                        <label class="form-check-label mb-50" for="is_show">Is Show This Course Form Now: </label>
                        <div class="form-check form-switch form-check-success mb-1">
                            <input type="checkbox" class="form-check-input" id="is_show" v-model="updateForm.is_show"
                                   :true-value="true" :false-value="false" />
                            <label class="form-check-label" for="is_show">
                                    <span class="switch-icon-left">
                                        <Icon title="check" />
                                    </span>
                                <span class="switch-icon-right">
                                        <Icon title="x" />
                                    </span>
                            </label>
                        </div>
                    </div>
                </div>
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
    <!-- Modal to add new user Ends-->


    <!--    edit student modal-->
    <Modal id="editUsrItem" title="Edit New User" v-vb-is:modal>
        <form @submit.prevent="updateStudent(showData.id)">
            <div class="modal-body">
                <Text v-model="updateForm.name" type="text" label="Name" placeholder="Name"
                      :error="props.errors.name"/>
                <Text v-model="updateForm.phone" type="text" label="Phone" placeholder="Phone number"
                      :error="props.errors.phone"/>
                <Text v-model="updateForm.email" type="email" label="Email" placeholder="Email Address"
                      :error="props.errors.email"/>
                <Image v-model="updateForm.photo" label="Photo" :error="props.errors.photo"/>
                <Image v-model="updateForm.certificate" label="Certificate" :error="props.errors.certificate"/>
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


    <!--    edit student modal-->
    <Modal id="showCertificate" title="Show Certificate" v-vb-is:modal size="lg">
        <img :src="user.certificate" alt="">
    </Modal>

    </Layout>


    <!--
<div class="row match-height">
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
                <h2 class="d-flex align-items-center text-black-50 mb-1">Job Preference</h2>
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="text-capitalize">{{ user.course }}</h3>
                    <h5>{{ user.user?.exp_min_sal }} LPA- {{ user.user?.exp_max_sal }}LPA</h5>
                </div>
                <p class="mb-0 pb-0">{{ user.user?.types }}</p>
                <p class="my-0 py-0">{{ user.user?.division.name }}, {{ user.user?.district.name }}</p>
                <hr class="my-1">
                <div class="d-flex flex-column">
                    <div class="">
                        Job course : <span class="badge border-success text-success">{{ user.user.course.name }}</span>
                    </div>
                    <div class="">
                        Sub user.courses: <span class="badge border-warning text-warning" v-for="cat in subCats">{{ cat.name }}</span>
                    </div>
                    <div>
                        Child user.courses: <span class="badge border-danger text-danger" v-for="cat in cCat">{{ cat.name }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <h2 class="d-flex align-items-center text-black-50 mb-1">Working Experience</h2>
                <div class="d-flex align-items-center justify-content-start">
                    <h3 class="text-capitalize">{{ user.company_name }}</h3>
                    <h5>{{ date(user.user?.start_date) }} - {{ date(user.user?.end_date) }}</h5>
                </div>
                <p class="mb-0 pb-0">{{ user.user?.designation }}</p>
                <p class="mt-1">{{ user.user?.experience }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h2 class="d-flex align-items-center text-black-50 mb-1">Education</h2>
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="text-capitalize">{{ user.user?.university }}</h3>
                    <h5>{{ date(user.user?.uni_start_date) }} - {{ date(user.user?.uni_end_date) }}</h5>
                </div>
                <p class="mb-0 pb-0">{{ user.user?.education_level?.label }} / {{
                    user.user?.educaiton?.education_name }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h2 class="d-flex align-items-center text-black-50 mb-1">
                    Curriculum Vitae / Resume
                </h2>
                <div class="panel panel-default bootcards-file">
                    <div class="list-group">
                        <div class="list-group-item">
                            <a href="#">
                                <vue-feather type="file-text"/>
                            </a>
                            <h4 class="list-group-item-heading">
                                <a :href="resume" target="_blank">{{ user.name+"CV-Resume.PDF" }}</a>
                            </h4>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <a :href="resume" target="_blank" class="btn btn-primary mt-1">
                            Download
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <h2 class="d-flex align-items-center text-black-50 mb-1">
                    Skills
                </h2>
                <span class="badge bg-dark text-white" style="margin-right: 2px;" v-for="skill in skills">{{ skill.value }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
                <h2 class="d-flex align-items-center text-black-50 mb-1">
                    Protfollue
                </h2>
                <a :href="user.portfolio_url" target="_blank">{{ user.portfolio_url }}</a>
            </div>
        </div>
    </div>
</div>-->
</template>

<script setup>
    import date from '@/Composable/TimeFormate.js'
    import Icon from '@/Components/Icon.vue'
    import Modal from '@/Components/Modal.vue';
    import Datepicker from 'vue3-datepicker'
    import Text from '@/Components/form/Text.vue'
    import Image from '@/Components/form/Image.vue'
    import Swal from "sweetalert2";
    import axios from "axios";
    import {ref} from "vue";
    import {router, useForm} from "@inertiajs/vue3";
    import Layout from '@/Shared/Layout.vue'

    let props = defineProps({
        user: Object,
        courses:[],
        allCourses:[],
        errors:'',
        create:'',
        zoomes:'',
        edit_url:''
    })

    let createForm = useForm({
        course_id: '',
        access_time:'',
        access_type:'',
        enroll_start:'',
        enroll_end:'',
        is_assinged:1,
        is_show:'',
        student_id : props.user.id
    });


    let updateForm = useForm({
        course_id: '',
        access_time:'',
        access_type:'',
        enroll_start:'',
        enroll_end:'',
        is_show: false,
        student_id : props.user.id
    });
    let updateUserForm = useForm({
        name: '',
        email: '',
        phone: '',
        designation: '',
        photo: '',
    });


    let assignNewCourse = () =>{
        createForm.get(props.create, {
            onSuccess: () => {
                document.getElementById('assignNewCourse').$vb.modal.hide()
                createForm.reset()
            }
        })
    }
    let showData = ref([])

    let editItem = (id) => {
        axios.get(`/student/course-details/${id}`).then(res =>{
            showData.value = res.data.id,
            updateForm.access_time = res.data.duration,
            updateForm.access_type =res.data.duration_type,
            document.getElementById('editItem').$vb.modal.show()
        }).catch(err => {
            console.log(err)
        })
    }

    let updateAssignCourse = (id) => {
        axios.put(`/student/course-details-update/${id}`, updateForm).then(res => {
            Swal.fire(
                'Updated!',
                'Your file has been Update.',
                'success'
            )
            location.reload();
            document.getElementById('editItem').$vb.modal.hide()
        });
    }


    let deleteItemModal = (id) => {
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
                router.delete(`/panel/student/delete/${id}`, {
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
                        // router.replace('/panel/students')
                    }
                })
            }
        })
    };


    let avticationStatus = (value, status) => {
        router.post(`/panel/student/course-stratus/${value}?show_status=${status}`, {
            onSuccess: () => {
                Swal.fire(
                    'Deactivated!',
                    'This account has been deactivated.',
                    'success'
                )
            }
        })
    }

    let editUserItem = (show_url) => {
        axios.get(show_url).then(res =>{

            console.log(res);

            showData.value = res;
            updateUserForm.name = res.data.name
            updateUserForm.email = res.data.email
            updateUserForm.phone =  res.data.phone
            updateUserForm.designation = res.data.designation
            updateUserForm.photo = res.data.photo
            updateUserForm.certificate = res.data.certificate
            document.getElementById('editUsrItem').$vb.modal.show()
        }).catch(err => {
            console.log(err)
        })
    }

    const showCertificate = () =>{
        document.getElementById('showCertificate').$vb.modal.show()
    }

</script>

