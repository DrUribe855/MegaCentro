<template>
  <div>
    <v-app>
      <v-main>
        <v-toolbar
          flat
        >
          <v-btn
            outlined
            class="mr-4"
            color="grey darken-2"
            @click="setToday"
          >
            Mes de actual
          </v-btn>
          <v-btn
            fab
            text
            small
            color="grey darken-2"
            @click="prev"
          >
            <v-icon small>
              mdi-chevron-left
            </v-icon>
          </v-btn>
          <v-btn
            fab
            text
            small
            color="grey darken-2"
            @click="next"
          >
            <v-icon small>
              mdi-chevron-right
            </v-icon>
          </v-btn>
          <v-toolbar-title>
            {{ date }}
          </v-toolbar-title>
          <v-spacer></v-spacer>
          <v-menu
            bottom
            right
          >
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                outlined
                color="grey darken-2"
                v-bind="attrs"
                v-on="on"
              >
                <span>{{ typeToLabel[type] }}</span>
                <v-icon right>
                  mdi-menu-down
                </v-icon>
              </v-btn>
            </template>
            <v-list>
              <v-list-item @click="type = 'day'">
                <v-list-item-title>Day</v-list-item-title>
              </v-list-item>
              <v-list-item @click="type = 'week'">
                <v-list-item-title>Week</v-list-item-title>
              </v-list-item>
              <v-list-item @click="type = 'month'">
                <v-list-item-title>Month</v-list-item-title>
              </v-list-item>
              <v-list-item @click="type = '4day'">
                <v-list-item-title>4 days</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
        </v-toolbar>
        <div class="container">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th rowspan="3" style="padding-bottom: 6em; padding-left: 1em;">FECHA</th>
                  <th colspan="4" class="text-center">RESIDUOS NO PELIGROSOS</th>
                  <th colspan="12" class="text-center">RESIDUOS PELIGROSOS</th>
                </tr>
                <tr>
                  <th class="text-center" colspan="4"></th>
                  <th class="text-center" colspan="4">INFECCIOSOS O RIESGO BIOLOGICO</th>
                  <th class="text-center" colspan="6">QUIMICOS</th>
                  <th class="text-center" colspan="2">RADIACTIVOS</th>
                </tr>
                <tr>
                  <th class="text-center">BIODEGRADABLES (Kg)</th>
                  <th class="text-center">RECICLABES (Kg)</th>
                  <th class="text-center">INERTES (Kg)</th>
                  <th class="text-center">ORDINARIOS-COMUNES (Kg)</th>
                  <th class="text-center">BIOSANITARIOS (Kg)</th>
                  <th class="text-center">ANATOMOPATOLOGICOS (Kg)</th>
                  <th class="text-center">CORTOPUNZANTES (Kg)</th>
                  <th class="text-center">ANIMALES (Kg)</th>
                  <th class="text-center">FARMACOS (Kg)</th>
                  <th class="text-center">CITOTÓXICOS (Kg)</th>
                  <th class="text-center">METALES PESADOS (Kg)</th>
                  <th class="text-center">REACTIVOS (Kg)</th>
                  <th class="text-center">CONTENEDORES PRESURIZADOS</th>
                  <th class="text-center">ACEITES USADOS (kg)</th>
                  <th class="text-center">FUENTES ABIERTAS</th>
                  <th class="text-center">FUENTES CERRADAS</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="i in index" class="text-center">
                  <td>{{ i }}</td>
                  <td v-for="residueId in residueIds">
                    {{ getResidueValue(residueId, i) }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </v-main>
    </v-app>
  </div>
</template>

<script>
  export default {
    data(){
      return {
        list_residues: [],
        index: 30,
        residueIds: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16],
        type: 'month',
        typeToLabel: {
          month: 'Month',
          week: 'Week',
          day: 'Day',
          '4day': '4 Days',
        },
        focus: new Date(),
        date: '',
        dateAxios: '',
        position: 0,
      }
    },

    created(){
      this.setToday();
      this.initialize(this.dateAxios);
    },

    methods: {
      initialize(date){
        console.log("date ", date);
        if (date != '') {
          axios.get(`/residue/generalShow/${date}`).then(res =>{
            console.log("Respuesta del servidor");
            console.log("Datos de consulta ",res.data.date);
            this.list_residues = res.data.residues;
          }).catch(error => {
            console.log("Error en servidor");
            console.log(error);
            console.log(error.response);
          }); 
        }
      },

      getResidueValue(residueId, day) {
        const residue = this.list_residues.find((r) => r.residue_id === residueId && r.day_of_month === day);

        if (residue) {
          return residue.total_weight;
        } else {
          return "0";
        }
      },

      setToday () {
        this.focus = new Date();
        const options = { month: 'long' };
        const month = this.focus.toLocaleDateString('es-ES', options);
        const year = this.focus.getFullYear();
        const monthNumber = this.focus.getMonth() + 1;
        this.position = monthNumber <= 9 ? this.position = '0'+ monthNumber : this.position = monthNumber;
        this.date = month+ ' '+ year;
        this.dateAxios = year+'-'+monthNumber;
        this.initialize(this.dateAxios);
      },
      prev () {
        const newFocus = new Date(this.focus);
        newFocus.setMonth(newFocus.getMonth() - 1);
        const options = { month: 'long' };
        const monthName = newFocus.toLocaleDateString('es-ES', options); 
        const year = newFocus.getFullYear();
        this.focus = newFocus;
        this.date = monthName+ ' '+ year
        this.position--;
        if (this.position == 0) {
          this.position = 12;
        }
        if (this.position <= 9 ) {
          this.position = '0'+this.position--;
        }
        this.dateAxios = year+'-'+this.position;
        console.log("FECHA ",this.dateAxios);
        this.initialize(this.dateAxios);
      },
      next () {
        const newFocus = new Date(this.focus);
        newFocus.setMonth(newFocus.getMonth() + 1);
        const options = { month: 'long' };
        const monthName = newFocus.toLocaleDateString('es-ES', options);
        const year = newFocus.getFullYear();
        this.focus = newFocus;
        this.date = monthName+ ' '+ year
        this.position++;
        if (this.position > 12) {
          this.position = 1;
        }
        if (this.position <= 9 ) {
          this.position = '0'+this.position++;
        }
        this.dateAxios = year+'-'+this.position;
        this.initialize(this.dateAxios);
        console.log(this.date);
      },
    }
  }
</script>