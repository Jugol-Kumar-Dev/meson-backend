<template>

    <Head title="My Courses" />
    <section class="app-order-list">
        <div class="container w-100" style="min-height: 100vh; max-width:100% !important;">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body d-md-flex gap-3">
                            <div style="height: 100%">
                                <img class="img-fluid rounded mb-75"
                                     :src="course.cover"
                                     :alt="course.name" width="150" />
                            </div>
                            <div>
                                <div>
                                    <p>Title:</p>
                                    <h2>{{ course.name }}</h2>
                                </div>

                                <div>
                                    <p>Description:</p>
                                    <p>{{ course.description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="alert alert-danger py-2 px-1" v-if="$page.props?.errors[0]">
                    {{  $page.props?.errors[0] }}
                </div>
                <div class="col-12">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab"
                                    data-bs-toggle="tab" data-bs-target="#home"
                                    type="button" role="tab" aria-controls="home"
                                    aria-selected="true">Live Exam</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab"
                                    data-bs-toggle="tab" data-bs-target="#profile"
                                    type="button" role="tab" aria-controls="profile"
                                    aria-selected="true">Practice Exam</button>
                        </li>
                        <!--                    <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="contact-tab" data-bs-toggle="tab"
                                                        data-bs-target="#contact" type="button" role="tab"
                                                        aria-controls="contact" aria-selected="false">Lesson List</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="video-tab" data-bs-toggle="tab"
                                                        data-bs-target="#video" type="button" role="tab"
                                                        aria-controls="video" aria-selected="false">Lesson Videos</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="students-tab" data-bs-toggle="tab"
                                                        data-bs-target="#students" type="button" role="tab"
                                                        aria-controls="students" aria-selected="false">Enrolled Students</button>
                                            </li>-->
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="row mt-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h4>Live Exam</h4>
                                </div>

                                <table class="user-list-table table">
                                    <thead class="table-light">
                                    <tr class="">
                                        <th class="sorting">Title</th>
                                        <th class="sorting">Total Questions</th>
                                        <th class="sorting">Time Details</th>
                                        <th class="sorting">Duration</th>
                                        <th class="sorting">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(mocktest, index) in mainMocks" :key="mocktest.id">
                                        <td>{{ mocktest?.name }}</td>
                                        <td>{{ mocktest?.total_q ?? mocktest?.questionCount }}</td>
                                        <td>
                                            <div class="d-flex flex-column" v-if="mocktest?.exam_type === 'main' && mocktest?.start_time && mocktest?.end_time">
                                                <span>Start Time: {{ moment( mocktest?.start_time)?.format('lll') }}</span>
                                                <span>End Time: {{ moment(mocktest?.end_time)?.format('lll') }}</span>
                                            </div>
                                            <div v-else>
                                                {{ mocktest?.duration }} Min
                                            </div>
                                        </td>
                                        <td><span>{{ mocktest?.duration }} Min</span></td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a class="btn btn-success" :href="`/mocktest/${mocktest.id}`">Start now</a>
                                                <a class="btn btn-primary" :href="`/mocktest-result/${mocktest.id}`">Show Result</a>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="row mt-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h4>Practice Exam</h4>
                                </div>
                                <table class="user-list-table table">
                                    <thead class="table-light">
                                        <tr class="">
                                            <th class="sorting">Title</th>
                                            <th class="sorting">Total Questions</th>
                                            <th class="sorting">Time Details</th>
<!--                                            <th class="sorting">Pass Mark</th>-->
                                            <th class="sorting">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(mocktest, index) in practiceMocks" :key="mocktest.id">
                                        <td>{{ mocktest?.name }}</td>
                                        <td>{{ mocktest?.total_q ?? mocktest?.questionCount }}</td>
                                        <td>{{ mocktest?.duration }} Min</td>
<!--                                        <td><span class="fw-bold text-capitalize">{{ mocktest?.pass_mark ?? '-&#45;&#45;' }}</span></td>-->
                                        <td>
                                            <div class="d-flex gap-1">
                                                <Link class="btn btn-success" :href="`/mocktest/${mocktest.id}`">Start now</Link>
                                                <a class="btn btn-primary" :href="`/mocktest-result/${mocktest.id}`">Show Result</a>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--                    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                                <div class="row mt-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <h4>All Lessons List</h4>
                                                        <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal"
                                                                data-bs-target="#addNewChapter">Add New Lesson</button>
                                                    </div>
                                                    <table class="user-list-table table table-striped">
                                                        <thead class="table-light">
                                                        <tr class="">
                                                            <th class="sorting">SL.</th>
                                                            <th class="sorting">Title</th>
                                                            <th class="sorting">Details</th>
                                                            <th class="sorting">Videos Count</th>
                                                            <th class="sorting">Status</th>
                                                            <th class="sorting">Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="(chapter, i) in course.chapters" :key="chapter.id">
                                                            <td>{{ i+1 }}</td>
                                                            <td>{{ chapter.chapter_title }}</td>
                                                            <td>{{ chapter.chapter_details }}</td>
                                                            <td>{{ chapter.videos?.length }}</td>
                                                            <td>{{ chapter.status }}</td>
                                                            <td class="d-flex gap-1">
                                                                <button class="btn btn-sm btn-info">
                                                                    <span class="dropdown-item cursor-pointer"
                                                                          @click="editChapterItem(props.chapter_url, chapter.id)">
                                                                            <Icon title="pencil"/>
                                                                       <span class="ms-1">edit</span>
                                                                    </span>
                                                                </button>
                                                                <button class="btn btn-sm btn-danger">
                                                                    <span class="dropdown-item cursor-pointer"
                                                                          @click="deleteItem(props.chapter_url, chapter.id)">
                                                                            <Icon title="trash"/>
                                                                       <span class="ms-1">Delete</span>
                                                                    </span>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="video" role="tabpanel" aria-labelledby="video-tab">
                                                <div class="row mt-3">
                                                    <div class="col-12 mx-auto">
                                                        <div class="accordion" id="accordionExample">
                                                            <div v-if="lessonVideos.length > 0" class="accordion-item mb-2">
                                                                <h2 class="accordion-header" id="headingOne">
                                                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                                        <p> videos Without Chapter</p>
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                                    <div class="accordion-body">
                                                                        <strong>
                                                                            All Videos Without Chapter ({{ lessonVideos.length }} videos)
                                                                        </strong>
                                                                        <div class="row mt-5">
                                                                            <div class="col-md-11 col-12 ms-md-auto md-overflow-x-scroll">
                                                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                                                    <h4>All Videos In This Lessons</h4>
                                                                                    <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal"
                                                                                            data-bs-target="#createNewLesson">Add Video Or File</button>
                                                                                </div>
                                                                                <table class="user-list-table table table-responsive">
                                                                                    <thead class="table-light">
                                                                                    <tr class="">
                                                                                        <th class="sorting">SL.</th>
                                                                                        <th class="sorting">Title</th>
                                                                                        <th class="sorting">Status</th>
                                                                                        <th class="sorting">Actions</th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                    <tr v-for="(lesson, index) in lessonVideos" :key="lesson?.id">
                                                                                        <td>#{{ index+1 }}</td>
                                                                                        <td>
                                                                                            <small>{{ lesson?.name }}</small>
                                                                                        </td>
                                                                                        <td>
                                                                                            <span v-if="lesson?.status == '1'" class="badge badge-light-primary">Active</span>
                                                                                            <span v-else class="badge badge-light-warning">Draft</span>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="btn-group dropdown dropdown-icon-wrapper">
                                                                                                <button type="button"
                                                                                                        class="btn dropdown-toggle dropdown-toggle-split waves-effect waves-float waves-light"
                                                                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                                                                                </button>

                                                                                                <div class="dropdown-menu">
                                                                                                    <span class="dropdown-item"
                                                                                                          v-if="lesson.status == 1"
                                                                                                          @click="avticationStatus(lesson?.id, false)">
                                                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down-left"><line x1="17" y1="7" x2="7" y2="17"></line><polyline points="17 17 7 17 7 7"></polyline></svg>
                                                                                                        <span class="ms-1">Inactive</span>
                                                                                                    </span>
                                                                                                    <span class="dropdown-item"
                                                                                                          v-else @click="avticationStatus(lesson?.id, true)">
                                                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-up-right"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                                                                                                        <span class="ms-1">Active</span>
                                                                                                    </span>

                                                                                                    <span v-if="lesson.content != null" class="dropdown-item"
                                                                                                          @click="showFile(lesson?.content, lesson.name)">
                                                                                                                <Icon title="eye"/>
                                                                                                               <span class="ms-1">File</span>
                                                                                                    </span>

                                                                                                    <span v-if="lesson.file != null" class="dropdown-item"
                                                                                                          @click="showPDF(lesson?.file)">
                                                                                                        <Icon title="eye"/>
                                                                                                        <span class="ms-1">File</span>
                                                                                                    </span>

                                                                                                    <span v-if="lesson.video" class="dropdown-item"
                                                                                                          @click="playVideo(lesson?.video, lesson.name)">
                                                                                                        <Icon title="play-circle"/>
                                                                                                       <span class="ms-1">Show</span>
                                                                                                    </span>

                                                                                                    <span class="dropdown-item"
                                                                                                          @click="editCategory(lesson?.id)">
                                                                                                        <Icon title="pencil"/>
                                                                                                        <span class="ms-1">Edit</span>
                                                                                                    </span>

                                                                                                    <span class="dropdown-item"
                                                                                                          @click="deleteItem(props.lesson_index, lesson?.id)">
                                                                                                        <Icon title="trash"/>
                                                                                                       <span class="ms-1">Delete</span>
                                                                                                    </span>
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="accordion-item mb-2" v-for="(chap,i) in course.chapters">
                                                                <h2 class="accordion-header" id="headingOnes">
                                                                    <button class="accordion-button text-"
                                                                            type="button"
                                                                            data-bs-toggle="collapse"
                                                                            :data-bs-target="`#collups${i}`"
                                                                            aria-expanded="true"
                                                                            :aria-controls="`collups${i}`">
                                                                        {{ chap?.chapter_title }}
                                                                    </button>
                                                                </h2>
                                                                <div :id="`collups${i}`"
                                                                     class="accordion-collapse collapse"
                                                                     :aria-labelledby="`collups${i}`"
                                                                     data-bs-parent="#accordionExample">
                                                                    <div class="accordion-body ">
                                                                        <strong class="">
                                                                            {{ chap?.chapter_title }} - {{ chap?.videos?.length }} Videos
                                                                        </strong>
                                                                        <p>{{ chap?.chapter_details }}</p>

                                                                        <div class="row mt-5">
                                                                            <div class="col-md-11 col-12 ms-md-auto md-overflow-x-scroll">
                                                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                                                    <h4>All Videos In This Lessons</h4>
                                                                                    <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal"
                                                                                            data-bs-target="#createNewLesson">Add Video Or File</button>
                                                                                </div>
                                                                                <table class="user-list-table table table-responsive">
                                                                                    <thead class="table-light">
                                                                                    <tr class="">
                                                                                        <th class="sorting">SL.</th>
                                                                                        <th class="sorting">Title</th>
                                                                                        <th class="sorting">Status</th>
                                                                                        <th class="sorting">Actions</th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                    <tr v-for="(lesson, index) in chap.videos" :key="lesson?.id">
                                                                                        <td>#{{ index+1 }}</td>
                                                                                        <td>
                                                                                            <small>{{ lesson?.name }}</small>
                                                                                        </td>
                                                                                        <td>
                                                                                            <span v-if="lesson?.status == '1'" class="badge badge-light-primary">Active</span>
                                                                                            <span v-else class="badge badge-light-warning">Draft</span>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="btn-group dropdown dropdown-icon-wrapper">
                                                                                                <button type="button"
                                                                                                        class="btn dropdown-toggle dropdown-toggle-split waves-effect waves-float waves-light"
                                                                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                                                                                </button>

                                                                                                <div class="dropdown-menu">
                                                                                                    <span class="dropdown-item"
                                                                                                          v-if="lesson.status == 1"
                                                                                                          @click="avticationStatus(lesson?.id, false)">
                                                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down-left"><line x1="17" y1="7" x2="7" y2="17"></line><polyline points="17 17 7 17 7 7"></polyline></svg>
                                                                                                        <span class="ms-1">Inactive</span>
                                                                                                    </span>
                                                                                                    <span class="dropdown-item"
                                                                                                          v-else @click="avticationStatus(lesson?.id, true)">
                                                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-up-right"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                                                                                                        <span class="ms-1">Active</span>
                                                                                                    </span>

                                                                                                    <span v-if="lesson.content != null" class="dropdown-item"
                                                                                                          @click="showFile(lesson?.content, lesson.name)">
                                                                                                                <Icon title="eye"/>
                                                                                                               <span class="ms-1">File</span>
                                                                                                    </span>

                                                                                                    <span v-if="lesson.file != null" class="dropdown-item"
                                                                                                          @click="showPDF(lesson?.file)">
                                                                                                        <Icon title="eye"/>
                                                                                                        <span class="ms-1">File</span>
                                                                                                    </span>

                                                                                                    <span v-if="lesson.video" class="dropdown-item"
                                                                                                          @click="playVideo(lesson?.video, lesson.name)">
                                                                                                        <Icon title="play-circle"/>
                                                                                                       <span class="ms-1">Show</span>
                                                                                                    </span>

                                                                                                    <span class="dropdown-item"
                                                                                                          @click="editCategory(lesson?.id)">
                                                                                                        <Icon title="pencil"/>
                                                                                                        <span class="ms-1">Edit</span>
                                                                                                    </span>

                                                                                                    <span class="dropdown-item"
                                                                                                          @click="deleteItem(props.lesson_index, lesson?.id)">
                                                                                                        <Icon title="trash"/>
                                                                                                       <span class="ms-1">Delete</span>
                                                                                                    </span>
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="students" role="tabpanel" aria-labelledby="students-tab">
                                                <div class="row mt-3">
                                                    <div class="col-12">
                                                        <div class="row">
                                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                                <h4>Enrolled Students</h4>
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
                                                                <tr v-for="student in course.orders" :key="student.id">
                                                                    <td>
                                                                        <div class="d-flex justify-content-left align-items-center">
                                                                            <div class="avatar-wrapper">
                                                                                <div class="avatar  me-1">
                                                                                    <img :src="student.user?.photo"
                                                                                         :alt="student.user?.name" height="32" width="32">
                                                                                </div>
                                                                            </div>
                                                                            <div class="d-flex flex-column">
                                                                                <div class="student_name text-truncate text-body">
                                                                                    <span class="fw-bolder">{{ student.user?.name }}</span>
                                                                                </div>
                                                                                <small class="emp_post text-muted">{{ student.user?.email }}</small>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>{{ student.user?.phone }}</td>
                                                                    <td>{{ student.user?.active_on }}</td>
                                                                    <td>
                                                                        <span class="badge bg-light-primary" v-if="student.user?.is_active">Activated</span>
                                                                        <span class="badge bg-light-warning" v-else>Deactivated</span>
                                                                    </td>
                                                                    <td>
                                                                        <div class="btn-group dropup dropdown-icon-wrapper">
                                                                            <a class="dropdown-item" :href="`/panel/students/${student.user?.id}`" target="_blank">
                                                                                <Icon title="eye"/>
                                                                                <span class="ms-1">Show</span>
                                                                            </a>
                                                                        </div>

                                                                        &lt;!&ndash;
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
                                                                                                    </div>&ndash;&gt;
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>-->
                    </div>
                </div>
            </div>
        </div>
    </section>

</template>

<script>
import SLayout from './Layout.vue'
export default {
    layout: SLayout,
};
</script>

<script setup>
import Icon from '@/Components/Icon.vue';
import {computed} from "vue";
import moment from "moment";
let props = defineProps({
    course: Object,
    url: String,
});

const mainMocks = computed(()=>{
    return props?.course?.mocktests?.filter(item =>item?.exam_type === 'main')
})

const practiceMocks = computed(()=>{
    return props?.course?.mocktests?.filter(item =>item?.exam_type !== 'main')
})


</script>

<style scoped>
    #webviewer {
        height: 400px;
        width: 100%;
    }
</style>
