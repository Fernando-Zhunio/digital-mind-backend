<template>
  <div style="min-height: 100vh;" class="bg-light">
    <div class="container py-3">
      <div>
        <h4 class="text-muted m-0">Modulo de administración</h4>
        <h1 class="font-bold">Administración de usuarios</h1>
        <div class="d-flex flex-wrap gap-2 justify-content-between">
          <div class="d-flex gap-2">
            <div class="px-3 py-2 rounded-3" style="background-color: #dadada">
              <select @change="changeFilter" class="bg-transparent border-0" v-model="department" placeholder="Seleccione una departamento">
                <option value="">Seleccione un departamento</option>
                <option :value="department.id" v-for="department in departments">{{ department.name }}</option>
              </select>
            </div>
            <div class="px-3 py-2 rounded-3" style="background-color: #dadada">
              <select @change="changeFilter" class="bg-transparent border-0" v-model="charge" placeholder="Seleccione un cargo">
                <option value="">Seleccione un cargo</option>
                <option :value="charge.id" v-for="charge in charges">{{ charge.name }}</option>
              </select>
            </div>
          </div>
          <div>
            <button class="btn btn-secondary" @click="openCreateOrEdit">Crear nuevo usuario</button>
          </div>
        </div>
      </div>
      <hr>

      <div class="overflow-auto bg-white">
        <div class=" p-4">
          <div>
            <table class="table border m-0">
              <thead>
                <tr>
                  <th>Usuario</th>
                  <th>Nombres</th>
                  <th>Apellidos</th>
                  <th>Departamento</th>
                  <th>Cargo</th>
                  <th>Email</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="user in users">
                  <td>{{ user.first_name[0] }}{{ user.second_name }}</td>
                  <td>{{ user.first_name }} {{ user.second_name }}</td>
                  <td>{{ user.surname }} {{ user.second_surname }}</td>
                  <td>{{ user.department.name }}</td>
                  <td>{{ user.charge.name }}</td>
                  <td>{{ user.email }}</td>
                  <td>
                    <div class="d-flex gap-2">
                      <button @click="() => { openCreateOrEdit(user.id) }" class="btn btn-sm text-success">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="green" height="1em"
                          viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                          <path
                            d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z" />
                        </svg>
                        Editar
                      </button>
                      <button @click="() => deleteUser(user.id)" class="btn btn-sm text-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="red" height="1em"
                          viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                          <path
                            d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z" />
                        </svg> Eliminar
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div style="background-color: rgb(241 241 241);" class="p-3 border">
              <span class="fw-bold">
                Total de Registros:  {{ users.length }}
              </span>
          </div>
        </div>
      </div>
    </div>
    <CreateOrEdit @create="addUser" @edit="editUser" :charges="charges" :departments="departments" :userEdit="userEdit"
      @close="isOpenDialog = false" v-if="isOpenDialog" />
  </div>
</template>

<script>
import CreateOrEdit from './components/CreateOrEdit.vue';
export default {
  components: {
    CreateOrEdit,
  },
  data() {
    return {
      users: [],
      charge: '',
      department: '',
      charges: [],
      departments: [],
      isOpenDialog: false,
      userEdit: null,
    }
  },
  mounted: async function () {
    this.fetchCustom('/admin/users/create').then(data => {
      this.departments = data.data.departments;
      this.charges = data.data.charges;
    })

    this.fetchCustom('/admin/users').then(data => {
      this.users = data.data.data;
    })

  },
  methods: {
    changeFilter: function () {
      this.fetchCustom(`/admin/users?department=${this.department}&charge=${this.charge}`).then(data => {
      this.users = data.data.data;
    })
    },
    close: function () {
      this.isOpenDialog = false;
      this.userEdit = null;
    },
    openCreateOrEdit: function (e = null) {
      this.isOpenDialog = true;
      if (e) {
        const userIndex = this.users.findIndex(x => x.id == e)
        if (userIndex > -1) {
          this.userEdit = { ...this.users[userIndex] }
        }
      }
    },
    fetchCustom: async function (url, data) {
      const res = await fetch(url, data);
      return await res.json()
    },
    addUser: function (user) {
      console.log({ user })
      this.users.unshift(user)
    },
    editUser: function (userEdit) {
      this.userEdit = null;
      let userIndex = this.users.findIndex(x => x.id == userEdit.id);
      console.log({ userEdit, user: userIndex })
      if (userIndex > -1) {
        this.users[userIndex] = userEdit;
      }
    },
    deleteUser: function (id) {
      Swal.fire({
        title: "Advertencia",
        text: "Esta seguro de que desea borrar este usuario",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "Cancelar"
      }).then((result) => {
        if (result.isConfirmed) {
          this.fetchCustom('/admin/users/' + id, {
            method: "DELETE", headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").getAttribute("content"),
            },
          }).then(res => {
            Swal.fire({
              title: "Eliminado!",
              text: "Este usuario a sido eliminado con éxito.",
              icon: "success"
            });
            const index = this.users.findIndex(x => x.id == id);
            if (index > -1) {
              this.users.splice(index, 1);
            }
          })
        }
      });
    }
  }
}
</script>

<style>
th {
  border: none !important;
}
</style>