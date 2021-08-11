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
            <div class="card-inner">
                <div>
                    <div class="card-title-group align-start gx-3 mb-3">
                        <div class="card-title">
                            <h6 class="title">Create New Employees</h6>
                        </div>
                        <div class="card-tools">
                            <nuxt-link to="/karyawan" class="btn btn-primary btn-dim"><em class="icon ni ni-back-ios"></em><span>Back to List</span></nuxt-link>
                        </div>
                    </div>
                    <form @submit.prevent="submit" class="gy-3">
                        <!-- <div class="row g-3 align-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-label mb-0" for="keyword">Employee Code</label>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <v-select :options="codes" :reduce="codes => codes" label="code" @search="fetchCodes" @input="setSelected"/>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <!-- <div class="row g-3 align-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-label mb-0" for="keyword">Entity Name</label>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" v-model='data.entityname' :disabled="!edit">
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <div class="row g-3 align-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-label mb-0" for="keyword">Employee Code</label>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" v-model="data.idk_kode_karyawan">
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
                                        <input type="text" class="form-control" v-model="data.idk_nama_karyawan">
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
                                        <v-select :options="gender" :reduce="gender => gender" label="description" @search="fetchGender" @input="fillGender"/>
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
                                        <input type="text" class="form-control" v-model="data.idk_telpon1_karyawan"/>
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
                                        <input type="text" class="form-control" v-model="data.idk_telpon2_karyawan"/>
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
                                        <input type="text" class="form-control" v-model="data.idk_email_karyawan"/>
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
                                        <Editor v-model="data.idk_alamat"/>
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
                                        <v-select :options="pid" :reduce="pid => pid" label="description" @search="fetchPid" @input="fillPid"/>
                                        <!-- <input type="text" class="form-control" v-model="data.idk_tipe_identitas"/> -->
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
                                        <input type="text" class="form-control" v-model="data.idk_nomor_identitas"/>
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
                                        <input type="text" class="form-control" v-model="data.idk_npwp"/>
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
                                        <input type="text" class="form-control" v-model="data.idk_jabatan"/>
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
                                        <input type="text" class="form-control" v-model="data.idk_departement"/>
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
                                        <input type="text" class="form-control" v-model="data.idk_cabang"/>
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
                                        <input type="text" class="form-control" v-model="data.idk_kantor_lokasi"/>
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
                                        <v-select :options="sk" :reduce="sk => sk" label="description" @search="fetchSk" @input="fillSk"/>
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
                                        <input type="text" class="form-control" v-model="data.idk_keterangan"/>
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
                                        <input  type="text" class="form-control" v-model="data.ibk_nama_bank"/>
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
                                        <input type="text" class="form-control" v-model="data.ibk_nomor_rek"/>
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
                                        <input type="text" class="form-control" v-model="data.ibk_nama_pada_rek"/>
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
                                        <input type="text" class="form-control" v-model="data.ibk_kantor_cabang_rek_dibuat"/>
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
                                        <input type="text" class="form-control" v-model="data.iln_tempat_lahir"/>
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
                                        <input type="datetime-local" class="form-control" v-model="data.iln_tanggal_lahir"
                                        />
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
                                        <input type="datetime-local" class="form-control" v-model="data.iln_tanggal_mulai_kerja"/>
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
                                        <v-select :options="religion" :reduce="religion => religion" label="description" @search="fetchAgama" @input="fillAgama"/>
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
                                        <v-select :options="academic" :reduce="academic => academic" label="description" @search="fetchPendidikan" @input="fillPendidikan"/>
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
                                        <v-select :options="marriage" :reduce="marriage => marriage" label="description" @search="fetchMarriage" @input="fillMarriage"/>
                                        <!-- <input type="text" class="form-control" v-model="data.iln_status_pernikahan"/> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-lg-2">
                            </div>
                            <div class="col-lg-10">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Editor from '@/components/Editor'

export default {
    components: {
        Editor
    },
    data () {
        return {
            data: {
                idk_kode_karyawan: "",
                idk_nama_karyawan: "",
                idk_alamat: "",
                idk_jenis_kelamin: "",
                idk_telpon1_karyawan: "",
                idk_telpon2_karyawan: "",
                idk_email_karyawan: "",
                idk_kantor_lokasi: "",
                idk_cabang: "",
                idk_departement: "",
                idk_status_karyawan: "",
                idk_keterangan: "",
                idk_tipe_identitas: "",
                ibk_nama_bank: "",
                ibk_nomor_rek: "",
                ibk_nama_pada_rek: "",
                ibk_kantor_cabang_rek_dibuat: "",
                iln_tempat_lahir: "",
                iln_tanggal_lahir: "",
                iln_tanggal_mulai_kerja: "",
                iln_agama: "",
                iln_pendidikan_terakhir: "",
                iln_status_pernikahan: "",
            },
            gender: [],
            pid: [],
            sk: [],
            religion: [],
            academic: [],
            marriage: []
        }
    },
    methods: {
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
        submit () {
            const data = { ...this.data, d: new Date().getTime() }

            this.$axios.$put('/api/v1/karyawan', data)
                .then((data) => {
                    this.$router.push({ path: `/karyawan/detail/${data.data.idk_kode_karyawan}` })
                })
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
    },
    created () {
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
