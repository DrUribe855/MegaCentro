<template>
  <v-app>
    <v-container>
      <v-snackbar v-model="snackbar" :timeout="timeout" color="success">
        {{ snackbarMessage }}
      </v-snackbar>

      <v-row>
        <v-col cols="12" sm="4">
          <v-select
            v-model="selectedClinic"
            :items="clinics"
            item-text="clinic_number"
            item-value="clinic_number"
            label="Filtrar por Clínica"
          ></v-select>
        </v-col>
        <v-col cols="12" sm="4">
          <v-select
            v-model="selectedSchedule"
            :items="schedules"
            label="Filtrar por Jornada"
          ></v-select>
        </v-col>
        <v-col cols="12" sm="4">
          <v-btn @click="clearFilters">Limpiar Filtros</v-btn>
        </v-col>
      </v-row>

      <v-data-table
        :headers="headers"
        :items="filteredCollectors"
        item-key="id"
        class="elevation-1"
      >
        <template v-slot:[`item.actions`]="{ item }">
          <v-icon small @click="editCollector(item)"> mdi-pencil </v-icon>
        </template>
      </v-data-table>

      <v-dialog v-model="dialog" max-width="600px">
        <v-card>
          <v-card-title>
            <span class="headline">Editar Recolecciones</span>
          </v-card-title>
          <v-card-text>
            <v-form ref="form">
              <v-container>
                <v-row>
                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model="editedItem.user.name"
                      label="Nombre"
                      disabled
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row
                  v-for="(waste, index) in editedItem.waste_collection"
                  :key="index"
                >
                  <v-col cols="12" sm="5">
                    <v-select
                      v-model="waste.id_residue"
                      :items="residues"
                      item-text="residue_name"
                      item-value="id"
                      label="Residuo"
                    ></v-select>
                  </v-col>
                  <v-col cols="12" sm="5">
                    <v-text-field
                      v-model="waste.weight"
                      label="Peso"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" sm="2">
                    <v-btn icon @click="removeWaste(index)">
                      <v-icon>mdi-delete</v-icon>
                    </v-btn>
                  </v-col>
                </v-row>
                <v-btn color="primary" @click="addWaste">Añadir Residuo</v-btn>
              </v-container>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text @click="closeDialog"
              >Cancelar</v-btn
            >
            <v-btn color="blue darken-1" text @click="saveCollector"
              >Guardar</v-btn
            >
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-container>
  </v-app>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      dialog: false,
      snackbar: false,
      snackbarMessage: "",
      timeout: 3000,
      headers: [
        { text: "Clinica", value: "clinic.clinic_number" },
        { text: "Recolector", value: "user.name" },
        { text: "Mes", value: "month" },
        { text: "Año", value: "year" },
        { text: "Jornada", value: "schedule" },
        { text: "Fecha de creacion", value: "created_at" },
        { text: "Peso (Total)", value: "total_weight" },
        { text: "Acciones", value: "actions", sortable: false },
      ],
      collectors: [],
      residues: [],
      clinics: [],
      schedules: ["Día", "Tarde", "Extra - 6:00 AM"], // Ajusta según tus datos
      selectedClinic: null,
      selectedSchedule: null,
      editedIndex: -1,
      editedItem: {
        user: {
          name: "",
        },
        waste_collection: [],
      },
      defaultItem: {
        user: {
          name: "",
        },
        waste_collection: [],
      },
    };
  },
  created() {
    this.getCollectors();
  },
  methods: {
    getCollectors() {
      axios
        .get(`/collector/get/admin`)
        .then((res) => {
          this.collectors = res.data.collectors.map((collector) => ({
            ...collector,
            total_weight: this.calculateTotalWeight(collector.waste_collection),
          }));
          this.residues = res.data.residues;
          this.clinics = [...new Set(res.data.collectors.map(collector => collector.clinic))];
        })
        .catch((error) => {
          console.log(error.response);
        });
    },
    calculateTotalWeight(waste_collection) {
      return waste_collection.reduce(
        (total, waste) => total + (waste.weight || 0),
        0
      );
    },
    editCollector(item) {
      this.editedIndex = this.collectors.indexOf(item);
      this.editedItem = Object.assign({}, item, {
        waste_collection: item.waste_collection.map((waste) => ({ ...waste })),
      });
      this.dialog = true;
    },
    closeDialog() {
      this.dialog = false;
      this.editedItem = Object.assign({}, this.defaultItem);
      this.editedIndex = -1;
    },
    saveCollector() {
      if (this.editedIndex > -1) {
        axios
          .put(`/collector/update/admin/${this.editedItem.id}`, {
            waste_collection: this.editedItem.waste_collection,
          })
          .then((res) => {
            this.getCollectors();
            this.snackbarMessage = "Recolección actualizada con éxito";
            this.snackbar = true;
          })
          .catch((error) => {
            console.log(error.response);
          });
      }
      this.closeDialog();
    },
    addWaste() {
      this.editedItem.waste_collection.push({ id_residue: null, weight: null });
    },
    removeWaste(index) {
      this.editedItem.waste_collection.splice(index, 1);
    },
    clearFilters() {
      this.selectedClinic = null;
      this.selectedSchedule = null;
    },
  },
  computed: {
    filteredCollectors() {
      return this.collectors.filter((collector) => {
        return (
          (!this.selectedClinic || collector.clinic.clinic_number === this.selectedClinic) &&
          (!this.selectedSchedule || collector.schedule === this.selectedSchedule)
        );
      });
    },
  },
};
</script>

<style scoped>
.headline {
  font-weight: bold;
}
</style>
