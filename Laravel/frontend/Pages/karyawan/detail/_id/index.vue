<template>
    <div>
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">Employees</h3>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-inner" v-if="data">
                <div class="card-title-group align-start gx-3 mb-3">
                    <div class="card-title">
                        <h6 class="title">{{ edit ? 'Editing' : 'Viewing' }} #{{ $route.params.id }}</h6>
                    </div>
                    <div class="card-tools">
                        <nuxt-link to="/karyawan" class="btn btn-primary btn-dim"><em class="icon ni ni-back-ios"></em><span>Back to List</span></nuxt-link>
                    </div>
                </div>
                <form @submit.prevent="update" class="gy-3">
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Employee Code</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" v-model="data.idk_kode_karyawan" :disabled="!edit">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Employee Name</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" v-model="data.idk_nama_karyawan" :disabled="!edit">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Jenis Kelamin</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <!-- <input type="text" class="form-control" v-model="data.idk_jenis_kelamin" :disabled="!edit"> -->
                                    <v-select :options="gender" v-model="data.idk_jenis_kelamin" :reduce="gender => gender" label="description" @search="fetchGender" @input="fillGender" :disabled="!edit"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Contact Number 1</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" v-model="data.idk_telpon1_karyawan" :disabled="!edit">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Contact Number 2</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" v-model="data.idk_telpon2_karyawan" :disabled="!edit">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Email</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" v-model="data.idk_email_karyawan" :disabled="!edit">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Address</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" v-model="data.idk_alamat" :disabled="!edit">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Identity Type</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <v-select :options="pid" v-model="data.idk_tipe_identitas" :reduce="pid => pid" label="description" @search="fetchPid" @input="fillPid" :disabled="!edit"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Indentity Number</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" v-model="data.idk_nomor_identitas" :disabled="!edit"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">N.P.W.P</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" v-model="data.idk_npwp" :disabled="!edit"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Jabatan</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" v-model="data.idk_jabatan" :disabled="!edit"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Department</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" v-model="data.idk_departement" :disabled="!edit"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Branch</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" v-model="data.idk_cabang" :disabled="!edit"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Office/Location</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" v-model="data.idk_kantor_lokasi" :disabled="!edit"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Employee Status</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <v-select :options="sk" v-model="data.idk_status_karyawan" :reduce="sk => sk" label="description" @search="fetchSk" @input="fillSk" :disabled="!edit"/>
                                    <!-- <input type="text" class="form-control" v-model="data.idk_status_karyawan"/> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Explain</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" v-model="data.idk_keterangan" :disabled="!edit"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-title-group align-start gx-3 mb-3">
                        <div class="card-title">
                            <h6 class="title">Employee Bank Information</h6>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Bank Name</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input  type="text" class="form-control" v-model="data.ibk_nama_bank" :disabled="!edit"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Bank Account Number</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" v-model="data.ibk_nomor_rek" :disabled="!edit"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Bank Account Name</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" v-model="data.ibk_nama_pada_rek" :disabled="!edit"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Bank Account Branch</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" v-model="data.ibk_kantor_cabang_rek_dibuat" :disabled="!edit"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-title-group align-start gx-3 mb-3">
                        <div class="card-title">
                            <h6 class="title">Other Informations</h6>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Place of birth</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" v-model="data.iln_tempat_lahir" :disabled="!edit"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Date of birth</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="date" class="form-control" v-model="data.iln_tanggal_lahir" :disabled="!edit"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Tanggal Mulai Kerja</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="date" class="form-control" v-model="data.iln_tanggal_mulai_kerja" :disabled="!edit"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Religion</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <v-select :options="religion" v-model="data.iln_agama" :reduce="religion => religion" label="description" @search="fetchAgama" @input="fillAgama" :disabled="!edit"/>
                                    <!-- <input type="text" class="form-control" v-model="data.iln_agama"/> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Latest Education</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <v-select :options="academic" v-model="data.iln_pendidikan_terakhir" :reduce="academic => academic" label="description" @search="fetchPendidikan" @input="fillPendidikan" :disabled="!edit"/>
                                    <!-- <input type="text" class="form-control" v-model="data.iln_pendidikan_terakhir"/> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Marital Status</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <v-select :options="marriage" v-model="data.iln_status_pernikahan" :reduce="marriage => marriage" label="description" @search="fetchMarriage" @input="fillMarriage" :disabled="!edit"/>
                                    <!-- <input type="text" class="form-control" v-model="data.iln_status_pernikahan"/> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0">Status</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <Label :active="data.is_active"/>
                            </div>
                        </div>
                    </div>
                    <div class="justify-content-end d-flex" v-if="edit">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>

                <div v-if="!edit" class="mt-5">
                    <strong>
                        Metadata Information
                    </strong>

                    <hr>
                    <Metadata :data="data"/>

                    <div class="g-3 d-flex justify-content-between">
                        <div>
                            <change-active
                                :active="data.is_active"
                                :callback="changeActive"
                            />
                        </div>
                        <div>
                            <button type="button" class="btn btn-light" @click="edit = true">Edit</button>
                            <nuxt-link to="/karyawan/create" class="btn btn-primary">Add New Data</nuxt-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Editor from '@/components/Editor';
import Label from '@/components/Label';
import ChangeActive from "@/components/ChangeActive";

export default {
    components: {
        Editor,
        Label,
        ChangeActive,
    },
    data () {
        return {
            data: null,
            edit: false,
            employees: [],
        };
    },
    methods: {
        parse (data) {
            this.data = data.data;
            if (this.data.employee) {
                this.employees.push(this.data.employee);
            }
        },
        fillGender (value) {
            this.data.idk_jenis_kelamin = value.description
            // this.data.entityname = value.description
        },
        fillPid (value) {
            this.data.idk_tipe_identitas = value.description
            // this.data.entityname = value.description
        },
        fillSk (value) {
            this.data.idk_status_karyawan = value.description
            // this.data.entityname = value.description
        },
        fillAgama (value) {
            this.data.iln_agama = value.description
            // this.data.entityname = value.description
        },
        fillPendidikan (value) {
            this.data.iln_pendidikan_terakhir = value.description
            // this.data.entityname = value.description
        },
        fillMarriage (value) {
            this.data.iln_status_pernikahan = value.description
            // this.data.entityname = value.description
        },
        fetchGender (search = 'GENDER') {
            this.$axios.$get('/api/v1/codes/getcode2', { params: { keyword: search, d: new Date().getTime() } })
                .then((response) => {
                    this.gender = response.data
                })
        },
        fetchPid (search = 'PID') {
            this.$axios.$get('/api/v1/codes/getcode2', { params: { keyword: search, d: new Date().getTime() } })
                .then((response) => {
                    this.pid = response.data
                })
        },
        fetchSk (search = 'EMPSTATUS') {
            this.$axios.$get('/api/v1/codes/getcode2', { params: { keyword: search, d: new Date().getTime() } })
                .then((response) => {
                    this.sk = response.data
                })
        },
        fetchAgama (search = 'RELIGION') {
            this.$axios.$get('/api/v1/codes/getcode2', { params: { keyword: search, d: new Date().getTime() } })
                .then((response) => {
                    this.religion = response.data
                })
        },
        fetchPendidikan (search = 'ACADEMIC') {
            this.$axios.$get('/api/v1/codes/getcode2', { params: { keyword: search, d: new Date().getTime() } })
                .then((response) => {
                    this.academic = response.data
                })
        },
        fetchMarriage (search = 'MARRIAGE') {
            this.$axios.$get('/api/v1/codes/getcode2', { params: { keyword: search, d: new Date().getTime() } })
                .then((response) => {
                    this.marriage = response.data
                })
        },
        fetch () {
            this.$axios.$get(`/api/v1/karyawan/${this.$route.params.id}`, { params: { d: new Date().getTime() } })
                .then((data) => {
                    this.parse(data);
                });
        },
        update () {
            const data = { ...this.data, d: new Date().getTime() }

            this.$axios.$patch(`/api/v1/karyawan/${this.data.idk_kode_karyawan}`, data)
                .then((data) => {
                    this.parse(data);
                    this.edit = false;
                });
        },
        changeActive() {
            this.$axios
                .$post(
                `/api/v1/karyawan/${this.data.idk_kode_karyawan}/change_active`,
                {
                    d: new Date().getTime(),
                }
                )
                .then((data) => {
                this.parse(data);
                });
            this.$axios
                .$post(
                `/api/v1/karyawan/${this.data.idk_kode_karyawan}/change_delete`,
                {
                    d: new Date().getTime(),
                }
                )
                .then((data) => {
                this.parse(data);
                });
        }
    },
    created () {
        this.fetch();
        this.fetchGender();
        this.fetchPid();
        this.fetchSk();
        this.fetchAgama();
        this.fetchPendidikan();
        this.fetchMarriage();
    }
}
</script>

<style scoped>
@import "@/static/flag/css/flag-icon.min.css";
</style>
