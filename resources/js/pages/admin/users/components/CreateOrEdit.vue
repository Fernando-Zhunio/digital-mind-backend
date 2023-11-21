<template>
    <div class="dialog w-100 vh-100 d-flex justify-content-center align-items-center">
        <div class="col-md-5 col-10 bg-white p-4 rounded-3">
            <div class="mb-3">
                <label for="first_name" class="form-label">Primer nombre</label>
                <input v-model="first_name" type="text" class="form-control" id="first_name" placeholder="Primer nombre">
            </div>
            <div class="mb-3">
                <label for="first_name" class="form-label">Segundo nombre</label>
                <input v-model="second_name" type="text" class="form-control" id="first_name" placeholder="Segundo nombre">
            </div>
            <div class="mb-3">
                <label for="surname" class="form-label">Primer apellido</label>
                <input v-model="surname" type="text" class="form-control" id="surname" placeholder="Primer apellido">
            </div>
            <div class="mb-3">
                <label for="second_surname" class="form-label">Segundo apellido</label>
                <input v-model="second_surname" type="text" class="form-control" id="second_surname"
                    placeholder="Segundo apellido">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input v-model="email" type="email" class="form-control" id="email" placeholder="Correo electrónico">
            </div>
            <div class="mb-3">
                <label for="charge_id" class="form-label">Cargo</label>
                <select v-model="department_id" type="text" class="form-control" id="charge_id" placeholder="">
                    <option value="">Seleccione un cargo</option>
                    <option :value="charge.id" v-for="charge in charges">{{ charge.name }}</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="department_id" class="form-label"></label>
                <select v-model="charge_id" type="text" class="form-control" id="second_surname"
                    placeholder="Segundo apellido">
                    <option value="">Seleccione un departamento</option>
                    <option :value="department.id" v-for="department in departments">{{ department.name }}
                    </option>
                </select>
            </div>
            <div class="d-flex gap-2">
                <button :disabled="isLoading" @click="saveInServer" class="btn btn-primary">Guardar</button>
                <button :disabled="isLoading" @click="close" class="btn btn-danger">Cerrar</button>
            </div>
        </div>
    </div>
</template>

<script>
import { convertResponse422 } from '../../../../tools/tools'

export default {
    props: {
        departments: [],
        charges: [],
        userEdit: {
            type: String,
            default: null
        },
    },
    data() {
        return {
            isLoading: false,
            first_name: null,
            second_name: null,
            surname: null,
            second_surname: null,
            department_id: null,
            charge_id: null,
            email: null
        }
    },
    mounted: function () {
        if (this.userEdit) {
            this.first_name = this.userEdit.first_name
            this.second_name = this.userEdit.second_name
            this.surname = this.userEdit.surname
            this.second_surname = this.userEdit.second_surname
            this.department_id = this.userEdit.department_id
            this.charge_id = this.userEdit.charge_id
            this.email = this.userEdit.email
        }
    },
    methods: {
        close: function () {
            this.$emit('close')
        },
        saveInServer: async function () {
            
                this.isLoading = true;
                const res = await fetch(`/admin/${this.userEdit ? 'users/' + this.userEdit.id : 'users'}`, {
                    method: this.userEdit ? 'PUT' : 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").getAttribute("content"),
                    },
                    body: JSON.stringify(this.getDataSend())
                })
                if (res.ok) {
                    Swal.fire({
                        title: 'Información',
                        text: 'Usuario guardo con éxito',
                        icon: 'success',
                        confirmButtonText: 'Cerrar'
                    })
                    const data = await res.json();
                    const event = this.userEdit ? 'edit': 'create';
                    this.$emit(event, data.data);
                    this.$emit('close')

                } else {
                    if (res.status == 422) {
                        const errors = await res.json();
                        const messages = convertResponse422(errors);
                        console.log(messages)
                        Swal.fire({
                            title: 'Error',
                            text: messages,
                            icon: 'error',
                            confirmButtonText: 'Cerrar'
                        })

                    }
                }
                this.isLoading = false
            
        },
        getDataSend() {
            return {
                first_name: this.first_name,
                second_name: this.second_name,
                surname: this.surname,
                second_surname: this.second_surname,
                department_id: this.department_id,
                charge_id: this.charge_id,
                email: this.email
            }
        }
    }
}
</script>

<style>
.dialog {
    position: fixed;
    z-index: 50;
    background: rgb(218 218 218 / 59%);
    top: 0;
    left: 0;
}
</style>