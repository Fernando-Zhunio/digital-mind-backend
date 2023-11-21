<template>
    <div style="position: fixed;z-index: 50;" class="bg-">
        <div>
            <div class="mb-3">
                <label for="first_name" class="form-label">Primer nombre</label>
                <input type="text" class="form-control" id="first_name" placeholder="Primer nombre">
            </div>
            <div class="mb-3">
                <label for="first_name" class="form-label">Segundo nombre</label>
                <input type="text" class="form-control" id="first_name" placeholder="Segundo nombre">
            </div>
            <div class="mb-3">
                <label for="surname" class="form-label">Primer apellido</label>
                <input type="text" class="form-control" id="surname" placeholder="Primer apellido">
            </div>
            <div class="mb-3">
                <label for="second_surname" class="form-label">Segundo apellido</label>
                <input type="text" class="form-control" id="second_surname" placeholder="Segundo apellido">
            </div>
            <div class="mb-3">
                <label for="charge_id" class="form-label">Cargo</label>
                <select type="text" class="form-control" id="charge_id" placeholder="">
                    <option value="">Seleccion e un cargo</option>
                    <option value="{{ charge.id }}" v-for="charge in props.charges">{{ charge.name }}</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="department_id" class="form-label"></label>
                <select type="text" class="form-control" id="second_surname" placeholder="Segundo apellido">
                    <option value="">Seleccione un departamento</option>
                    <option value="{{ department.id }}" v-for="department in props.departments">{{ department.name }}</option>
                </select>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        'departments': [],
        'charges': []
    },
    setup() {
        return {
            first_name: null,
            second_name: null,
            surname: null,
            second_surname: null,
            department_id: null,
            charge_id: null,
            email: null
        }
    }
}
</script>