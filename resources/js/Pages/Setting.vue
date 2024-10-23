<template>

    <Layout>
        <Head title="Slider List"/>

        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Settings</h2>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-3 d-flex flex-column gap-2">
                <div class="btn btn-outline-primary"
                     :class="{ 'bg-primary text-white fw-bolder': currentTab === 'profileSettins' }"
                     @click="setActiveTab('profileSettins')">
                    Profile Settings
                </div>
                <div class="btn btn-outline-primary"
                     :class="{ 'bg-primary text-white fw-bolder': currentTab === 'homesettings' }"
                     @click="setActiveTab('homesettings')">
                    Home Setting
                </div>
                <div class="btn btn-outline-primary"
                     :class="{ 'bg-primary text-white fw-bolder': currentTab === 'details' }"
                     @click="setActiveTab('details')">
                    Home Course Setting
                </div>
                <div class="btn btn-outline-primary"
                     :class="{ 'bg-primary text-white fw-bolder': currentTab === 'homeMessage' }"
                     @click="setActiveTab('homeMessage')">
                    Second Home Section
                </div>

                <div class="btn btn-outline-primary"
                     :class="{ 'bg-primary text-white fw-bolder': currentTab === 'counter' }"
                     @click="setActiveTab('counter')">
                    Success Rate
                </div>
                <div class="btn btn-outline-primary"
                     :class="{ 'bg-primary text-white fw-bolder': currentTab === 'notice' }"
                     @click="setActiveTab('notice')">
                    Notice
                </div>

<!--                <div class="btn btn-outline-primary"
                     :class="{ 'bg-primary text-white fw-bolder': currentTab === 'contactUs' }"
                     @click="setActiveTab('contactUs')">
                    Contact Us
                </div>
                <div class="btn btn-outline-primary"
                     :class="{ 'bg-primary text-white fw-bolder': currentTab === 'headerPages' }"
                     @click="setActiveTab('headerPages')">
                    Header Pages
                </div>
                <div class="btn btn-outline-primary"
                     :class="{ 'bg-primary text-white fw-bolder': currentTab === 'footerPages' }"
                     @click="setActiveTab('footerPages')">
                    Footer Pages
                </div>-->

            </div>
            <div class="col-md-9">
                <div class="card" v-if="currentTab === 'profileSettins'">
                    <div class="card-header d-flex gap-2 mb-1">
                        <h3 class="card-title m-0">Profile Settings</h3>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <Text class="w-100 mb-0" v-model="createForm.profile.name"
                              label="Site Name"
                              placeholder="App name"/>
                        <Text class="w-100 mb-0" v-model="createForm.profile.email"
                              label="Site Email"
                              placeholder="Email Address"/>
                        <Text class="w-100 mb-0" v-model="createForm.profile.phone"
                              label="Phone Number"
                              placeholder="Phone"/>
                        <Text class="w-100 mb-0" v-model="createForm.profile.whatsapp"
                              label="Whatsapp Number"
                              placeholder="Whatsapp"/>
                        <Text class="w-100 mb-0" v-model="createForm.profile.address"
                              label="Address"
                              placeholder="Address"/>
                        <button class="btn btn-success mt-5" @click="updateSettings">Update Settings</button>
                    </div>
                </div>


                <div class="card" v-if="currentTab === 'homesettings'">
                    <div class="card-header d-flex gap-2 mb-1">
                        <h3 class="card-title m-0">Home Settings</h3>
                    </div>
                    <div class="card-body d-flex flex-column gap-2">
                        <div>
                            <label>Header Categories</label>
                            <v-select v-model="createForm.headerCats"
                                      label="name"
                                      multiple="true"
                                      :options="data?.categories" :reduce="item => item.id"></v-select>
                        </div>
                        <div>
                            <label>Hero Categories</label>
                            <v-select v-model="createForm.heroCats"
                                      label="name"
                                      multiple="true"
                                      :options="data?.categories" :reduce="item => item.id"></v-select>
                        </div>

                        <div>
                            <label>Hero Courses</label>
                            <v-select v-model="createForm.heroCourses"
                                      label="name"
                                      multiple="true"
                                      :options="data?.course" :reduce="item => item.id"></v-select>
                        </div>
                        <div>
                            <label>Top 4 Category</label>
                            <v-select v-model="createForm.topForCats"
                                      label="name"
                                      multiple="true"
                                      :options="data?.categories" :reduce="item => item.id"></v-select>
                        </div>


                        <button class="btn btn-success mt-5" @click="updateSettings">Update Settings</button>
                    </div>
                </div>


                <div class="card" v-if="currentTab === 'details'">
                    <div class="card-header d-flex gap-2">
                        <h3 class="card-title m-0">Home page setting</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 border p-1" v-for="(item, index) in createForm.homeCourses">
                                <Text class="w-100 mb-0" v-model="createForm.homeCourses[index].section"
                                      placeholder="Section title"/>
                                <v-select v-model="createForm.homeCourses[index].courses"
                                          label="name"
                                          multiple="true"
                                          :options="data.course" :reduce="course => course.id"></v-select>
                                <div>
                                    <button type="button" class="btn mt-2 btn-sm btn-primary"
                                            v-if="index === 0" @click="addFaq">
                                        +
                                    </button>
                                    <button type="button" class="btn mt-2 btn-sm btn-secondary" v-else
                                            @click="removeFaq(index)">
                                        -
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-success mt-3" @click="updateSettings">Update Settings</button>
                    </div>
                </div>
                <div class="card" v-if="currentTab === 'homeMessage'">
                    <div class="card-header d-flex gap-2">
                        <h3 class="card-title m-0">Secon Home Section</h3>
                    </div>
                    <div class="card-body">
                        <Text v-model="createForm.secondSecond.sectionTitle" label="Section Title"/>

                        <label>Section Category</label>
                        <v-select v-model="createForm.secondSecond.categories"
                                  class="mb-1"
                                  label="name"
                                  multiple="true"
                                  :options="data?.categories" :reduce="page => page.id"></v-select>

                        <label>Section Course</label>
                        <v-select v-model="createForm.secondSecond.courses"
                                  label="name"
                                  multiple="true"
                                  :options="data?.course"
                                  :reduce="page => page.id">
                        </v-select>

                        <button class="btn btn-success mt-2" @click="updateSettings">Update Settings</button>
                    </div>
                </div>

                <div class="card" v-if="currentTab === 'counter'">
                    <div class="card-header d-flex gap-2 mb-1">
                        <h3 class="card-title m-0">Success Rate</h3>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <Text class="w-100 mb-0" v-model="createForm.counter.title"
                              label="Title"/>

                        <Text class="w-100 mb-0" v-model="createForm.counter.students"
                              label="Total Students"/>

                        <Text class="w-100 mb-0" v-model="createForm.counter.courses"
                              label="Runing Courses"/>
                        <Text class="w-100 mb-0" v-model="createForm.counter.certificate"
                              label="Provide Certificates"/>
                        <button class="btn btn-success mt-5" @click="updateSettings">Update Settings</button>
                    </div>
                </div>



                <div class="card" v-if="currentTab === 'notice'">
                    <div class="card-header d-flex gap-2 mb-1">
                        <h3 class="card-title m-0">Notice</h3>
                    </div>
                    <div class="card-body">
                        <label>End Time From Now</label>

                        <input type="date" v-model="createForm.notice.notice_date" class="form-control" placeholder="enter end date"/>

                        <Textarea v-model="createForm.notice.notice" style="height:500px;"
                                  placeholder="Notice..."/>
                        <button class="btn btn-success" @click="updateSettings">Update Settings</button>

                    </div>
                </div>


                <div class="card" v-if="currentTab === 'headerPages'">
                    <div class="card-header d-flex gap-2 mb-1">
                        <h3 class="card-title m-0">Header Pages</h3>
                    </div>
                    <div class="card-body">
                        <v-select v-model="createForm.headerpages"
                                  label="title"
                                  multiple="true"
                                  :options="pages" :reduce="page => page.id"></v-select>

                        <button class="btn btn-success mt-5" @click="updateSettings">Update Settings</button>
                    </div>
                </div>
                <div class="card" v-if="currentTab === 'footerPages'">
                    <div class="card-header d-flex gap-2 mb-1">
                        <h3 class="card-title m-0">Footer Pages</h3>
                    </div>
                    <div class="card-body">
                        <datepicker v-model="createForm.notice.notice_date"
                                    :minimumView="'time'"
                                    inputFormat="yyyy-MM-dd HH:mm"
                                    class="form-control"
                                    placeholder="Start Time"/>
                        <Textarea v-model="createForm.notice.notice" style="height:500px;"
                                  placeholder="Notice..."/>
                        <button class="btn btn-success mt-5" @click="updateSettings">Update Settings</button>
                    </div>
                </div>
            </div>
        </div>


    </Layout>


</template>

<script setup>
import Layout from "@/Shared/Layout.vue";
import TextInput from "@/Components/TextInput.vue";
import {useForm} from "@inertiajs/vue3";
import Text from "@/Components/form/Text.vue";
import {onMounted, ref} from "vue";
import Textarea from "@/Components/form/Textarea.vue";
import Datepicker from "vue3-datepicker";

const props = defineProps({
    pages: Array,
    data:Array,
    bSettings: Object,
})

const currentTab = ref('homesettings')
const setActiveTab = (event) => currentTab.value = event

let createForm = useForm({
    headerCats: props.bSettings.headerCats ?? '',
    heroCats: props.bSettings.heroCats ?? '',

    homeCourses: props.bSettings.homeCourses ?? '',
    heroCourses: props.bSettings.heroCourses ?? '',
    topForCats: props.bSettings.topForCats ?? '',

    secondSecond:  props.bSettings.secondSecond ?? {},
    profile: props?.bSettings.profile,
    counter: props?.bSettings.counter ?? {
        title:null,
        students:null,
        courses:null,
        certificate:null,
    },
    notice:props?.bSettings?.notice ?? {
      notice_date:null,
      notice:null
    },


    s4Title: props.bSettings.s4Title ?? '',
    s4Slogan: props.bSettings.s4Slogan ?? '',

    mstitle: props.bSettings.mstitle ?? '',
    msbody: props.bSettings.msbody ?? '',

    contactUs: props.bSettings.contactUs ?? '',

    faqpagetitle: props.bSettings.faqpagetitle ?? '',
    faqpageslogan: props.bSettings.faqpageslogan ?? '',

    headerpages: props.bSettings.headerpages ?? '',
    footerpages: props.bSettings.footerpages ?? '',

    footerText: props.bSettings.footerText ?? '',


    // logo_fabs   : props.bSettings.logo_fabs ?? '',
    // footer_logo   : props.bSettings.logo_fabs ?? '',
    // app_details : props.bSettings.details ?? '',
    // timezone    : props.bSettings.timezone?.tz ?? '',
    // country     : props.bSettings.country?.name ?? '',
    //
    // address     : props.bSettings.address ?? '',
    // email       : props.bSettings.email ?? '',
    // phone       : props.bSettings.phone ?? '',
    //
    // facebook_profile  : props.bSettings.facebook_profile ?? '',


})
const addFaq = () => {
    createForm.homeCourses.push(
        {
            section: null,
            courses: []
        }
    )
}
const removeFaq = (index) => createForm.homeCourses.splice(index, 1);

const updateSettings = () => {
    createForm.post('/panel/settings-update', {
        onSuccess: () => {
            alert("Setting Updated....")
        }
    })

}
// onMounted(()=> createForm.value = props.bSettings)

</script>

