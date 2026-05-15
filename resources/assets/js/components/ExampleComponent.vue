<!-- resources/assets/js/components/ExampleComponent.vue -->
<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Example Component</div>
                    <div class="card-body">
                        I'm an example component.
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    mounted() {
        console.log('Component mounted.')
    }
}
</script>












<template>
  <div class="calendar-wrapper">
    <!-- Flash Messages Component -->
    <flash-message ref="flashMessage"></flash-message>

    <!-- Main Calendar Area -->
    <div class="calendar-main">
      <!-- Calendar Header -->
      <div class="calendar-header mb-3">
        <!-- First Row: Title + Add Button -->
        <div class="row align-items-center mb-2">
          <div class="col-12 text-start">
            <h3 class="mb-0">
              <i class="fas fa-calendar-alt me-2"></i>
              {{ medecinName ? `Agenda Dr ${medecinName}` : 'Calendrier des Rendez-vous' }}
            </h3>
          </div>
        </div>

        <!-- ROW 2: Add Button -->
        <div class="row align-items-center mb-3">
          <div class="col-12 text-end">
            <button 
              v-if="canCreate"
              class="btn btn-primary"
              @click="openCreateModal"
            >
              <i class="fas fa-plus"></i>
              Nouveau Rendez-vous
            </button>
            <a 
              v-if="medecinId"
              :href="backUrl"
              class="btn btn-success ms-2"
            >
              <i class="fas fa-arrow-left"></i>
              Retour à l'agenda
            </a>
          </div>
        </div>
        
        <!-- Row 3: Navigation + View Buttons -->
        <div class="row align-items-center mb-3">
          <div class="col-md-4 d-flex align-items-center gap-2">
            <div class="btn-group" role="group">
              <button class="btn btn-secondary" @click="navigatePrev">
                <i class="fas fa-chevron-left"></i>
              </button>
              
              <button class="btn btn-secondary" @click="navigateNext">
                <i class="fas fa-chevron-right"></i>
              </button>

              <button class="btn btn-primary" @click="navigateToday">
                Aujourd'hui
              </button>
            </div>
          </div>

          <!-- CENTER: Month Title -->
          <div class="col-md-4 text-center">
            <h4 class="fw-bold text-capitalize mb-0">
              {{ calendarTitle }}
            </h4>
          </div>

            <div class="col-md-4 text-end">
              <div class="btn-group" role="group">
                <button 
                  class="btn btn-outline-secondary"
                  :class="{ 'active': currentView === 'dayGridMonth' }"
                  @click="changeView('dayGridMonth')"
                >
                  Mois
                </button>
                <button 
                  class="btn btn-outline-secondary"
                  :class="{ 'active': currentView === 'timeGridWeek' }"
                  @click="changeView('timeGridWeek')"
                >
                  Semaine
                </button>
                <button 
                  class="btn btn-outline-secondary"
                  :class="{ 'active': currentView === 'timeGridDay' }"
                  @click="changeView('timeGridDay')"
                >
                  Jour
                </button>
                <button 
                  class="btn btn-outline-secondary"
                  :class="{ 'active': currentView === 'listWeek' }"
                  @click="changeView('listWeek')"
                >
                  Liste
                </button>
              </div>
          </div>
        </div>
      </div>

      <!-- Status Legend -->
      <div class="status-legend mb-3">
        <h6 class="mb-2">Légende des statuts</h6>
        <div class="d-flex flex-wrap gap-2">
          <span class="legend-item">
            <span class="legend-color bg-steelblue"></span>
            <span class="legend-label">À venir</span>
          </span>
          <span class="legend-item">
            <span class="legend-color bg-darkcyan"></span>
            <span class="legend-label">Vu</span>
          </span>
          <span class="legend-item">
            <span class="legend-color bg-plum"></span>
            <span class="legend-label">Absence excusée</span>
          </span>
          <span class="legend-item">
            <span class="legend-color bg-slateblue"></span>
            <span class="legend-label">Absence non excusée</span>
          </span>
          <span class="legend-item">
            <span class="legend-color bg-tomato"></span>
            <span class="legend-label">Reporté</span>
          </span>
        </div>
      </div>

      <!-- Content Row: Sidebar and Calendar -->
      <div class="content-row" :class="{ 'with-sidebar': showSidebar }">
        <!-- Sidebar for Medecins -->
        <div v-if="showSidebar" class="medecin-sidebar">
          <h5 class="sidebar-title">
            <i class="fas fa-user-md"></i>
            Médecins
          </h5>
          <div class="medecin-list">
            <div
              v-for="medecin in medecins"
              :key="medecin.id"
              class="medecin-item"
              :class="{ active: selectedMedecinId === medecin.id }"
              @click="selectMedecin(medecin.id)"
            >
              <div class="medecin-name">
                Dr. {{ medecin.name }} {{ medecin.prenom }}
              </div>
            </div>
          </div>
        </div>

        <!-- Calendar Area -->
        <div class="calendar-area" :class="{ 'with-sidebar': showSidebar }">
          <!-- Loading Skeleton -->
          <div v-if="loading" class="calendar-skeleton">
            <div class="skeleton-header"></div>
            <div class="skeleton-grid">
              <div v-for="n in 35" :key="n" class="skeleton-cell"></div>
            </div>
          </div>

          <!-- Error Message -->
          <div v-if="error" class="alert alert-danger" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            {{ error }}
          </div>

          <!-- FullCalendar -->
          <div v-show="!loading && !error" class="calendar-container">
            <FullCalendar ref="fullCalendar" :options="calendarOptions" />
          </div>
        </div>
      </div>

      <!-- Create/Edit Event Modal -->
      <div 
        class="modal fade" 
        id="eventModal" 
        tabindex="-1" 
        ref="eventModal"
        aria-hidden="true"
        data-bs-backdrop="static"
      >
        <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">
                {{ editingEvent ? 'Modifier' : 'Nouveau' }} Rendez-vous
              </h5>
              <button 
                type="button" 
                class="btn-close" 
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            
            <form @submit.prevent="saveEvent">
              <div class="modal-body">
                <!-- Patient Selection with Search -->
                <div class="mb-3">
                  <label class="form-label">Patient <span class="text-danger">*</span></label>
                  
                  <!-- Add search input -->
                  <input 
                    v-model="patientSearchQuery"
                    type="text"
                    class="form-control mb-2"
                    placeholder="Rechercher un patient..."
                    @focus="loadAllPatients"
                  >

                  <select 
                    v-model="eventForm.patient_id" 
                    class="form-selec patient-select"
                    required
                    @scroll="handlePatientScroll"
                    @focus="loadAllPatients"
                  >
                    <option value="">Sélectionner un patient</option>
                    <option 
                      v-for="patient in allPatients" 
                      :key="patient.id" 
                      :value="patient.id"
                    >
                      {{ patient.name }} {{ patient.prenom }}
                    </option>
                    <option v-if="isLoadingPatients" disabled>
                      Chargement...
                    </option>
                  </select>
                  <small class="text-muted">
                    <i class="fas fa-search"></i>
                    Tapez pour rechercher ou faites défiler pour charger plus
                  </small>
                  <small class="text-muted">
                    <i class="fas fa-info-circle"></i>
                    Tous les patients sont affichés. Utilisez la barre de défilement pour naviguer.
                  </small>
                </div>

                <!-- Medecin Selection -->
                <div class="mb-3" v-if="!medecinId">
                  <label class="form-label">Médecin <span class="text-danger">*</span></label>
                  <select 
                    v-model="eventForm.medecin_id" 
                    class="form-select"
                    required
                  >
                    <option value="">Sélectionner un médecin</option>
                    <option 
                      v-for="medecin in medecins" 
                      :key="medecin.id" 
                      :value="medecin.id"
                    >
                      Dr. {{ medecin.name }} {{ medecin.prenom }}
                    </option>
                  </select>
                </div>

                <!-- Objet (Radio Buttons) -->
                <div class="mb-3">
                  <label class="form-label">Objet <span class="text-danger">*</span></label>
                  <div class="d-flex gap-3">
                    <div class="form-check">
                      <input 
                        v-model="eventForm.objet" 
                        class="form-check-input" 
                        type="radio" 
                        value="Consultation"
                        id="objetConsultation"
                        required
                      >
                      <label class="form-check-label" for="objetConsultation">
                        Consultation
                      </label>
                    </div>
                    <div class="form-check">
                      <input 
                        v-model="eventForm.objet" 
                        class="form-check-input" 
                        type="radio" 
                        value="Examen"
                        id="objetExamen"
                      >
                      <label class="form-check-label" for="objetExamen">
                        Examen
                      </label>
                    </div>
                    <div class="form-check">
                      <input 
                        v-model="eventForm.objet" 
                        class="form-check-input" 
                        type="radio" 
                        value="Acte"
                        id="objetActe"
                      >
                      <label class="form-check-label" for="objetActe">
                        Acte
                      </label>
                    </div>
                    <div class="form-check">
                      <input 
                        v-model="eventForm.objet" 
                        class="form-check-input" 
                        type="radio" 
                        value="Autres"
                        id="objetAutres"
                      >
                      <label class="form-check-label" for="objetAutres">
                        Autres
                      </label>
                    </div>
                  </div>
                </div>

                <!-- Title -->
                <div class="mb-3">
                  <label class="form-label">Titre</label>
                  <input 
                    v-model="eventForm.title" 
                    type="text" 
                    class="form-control"
                    placeholder="Généré automatiquement depuis le patient"
                    readonly
                  >
                </div>

                <!-- Date & Time -->
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Date de début <span class="text-danger">*</span></label>
                    <input 
                      v-model="eventForm.start_date" 
                      type="date" 
                      class="form-control"
                      required
                    >
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Heure de début <span class="text-danger">*</span></label>
                    <input 
                      v-model="eventForm.start_time" 
                      type="time" 
                      class="form-control"
                      step="900"
                      required
                    >
                    <small class="text-muted">Intervalles de 15 minutes</small>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Date de fin</label>
                    <input 
                      v-model="eventForm.end_date" 
                      type="date" 
                      class="form-control"
                    >
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Heure de fin <span class="text-danger">*</span></label>
                    <input 
                      v-model="eventForm.end_time" 
                      type="time" 
                      class="form-control"
                      step="900"
                      required
                    >
                    <small class="text-muted">Intervalles de 15 minutes</small>
                  </div>
                </div>

                <!-- Description -->
                <div class="mb-3">
                  <label class="form-label">Commentaire</label>
                  <textarea 
                    v-model="eventForm.description" 
                    class="form-control" 
                    rows="3"
                    placeholder="Notes ou informations complémentaires..."
                  ></textarea>
                </div>

                <!-- Statut - Conditional based on user role -->
                <div class="mb-3" v-if="editingEvent">
                  <label class="form-label">Statut</label>
                  <div class="btn-group w-100 flex-wrap" role="group">
                    <!-- Always available statuses -->
                    <input 
                      type="radio" 
                      class="btn-check" 
                      v-model="eventForm.statut"
                      value="a venir"
                      id="statutAvenir"
                    >
                    <label class="btn btn-steelblue status-btn" for="statutAvenir">
                      À venir
                    </label>
                    
                    <input 
                      type="radio" 
                      class="btn-check" 
                      v-model="eventForm.statut"
                      value="vu"
                      id="statutVu"
                    >
                    <label class="btn btn-darkcyan status-btn" for="statutVu">
                      Vu
                    </label>
                    
                    <!-- Restricted statuses for medecins -->
                    <template v-if="userRole !== 2">
                      <input 
                        type="radio" 
                        class="btn-check" 
                        v-model="eventForm.statut"
                        value="absence excusé"
                        id="statutExcuse"
                      >
                      <label class="btn btn-plum status-btn" for="statutExcuse">
                        Absence excusée
                      </label>
                      
                      <input 
                        type="radio" 
                        class="btn-check" 
                        v-model="eventForm.statut"
                        value="absence non excusé"
                        id="statutNonExcuse"
                      >
                      <label class="btn btn-slateblue status-btn" for="statutNonExcuse">
                        Absence non excusée
                      </label>
                    </template>
                    
                    <input 
                      type="radio" 
                      class="btn-check" 
                      v-model="eventForm.statut"
                      value="reporté"
                      id="statutReporte"
                    >
                    <label class="btn btn-tomato status-btn" for="statutReporte">
                      Reporté
                    </label>
                  </div>
                  <small class="text-muted d-block mt-2" v-if="userRole === 2">
                    <i class="fas fa-info-circle"></i>
                    En tant que médecin, vous pouvez changer le statut à "à venir", "vu" ou "reporté" uniquement.
                  </small>
                </div>
              </div>

              <!-- New Report Date/Time (shown only when "reporté" is selected) -->
              <div v-if="editingEvent && eventForm.statut === 'reporté'" class="modal-body pt-0">
                <div class="alert alert-info mb-0">
                  <h6 class="alert-heading">
                    <i class="fas fa-calendar-plus"></i>
                    Nouvelle date du rendez-vous
                  </h6>
                  <div class="row">
                    <div class="col-md-6 mb-2">
                      <label class="form-label">Nouvelle date <span class="text-danger">*</span></label>
                      <input 
                        v-model="eventForm.new_report_date" 
                        type="date" 
                        class="form-control"
                        :min="new Date().toISOString().split('T')[0]"
                        required
                      >
                    </div>
                    <div class="col-md-6 mb-2">
                      <label class="form-label">Nouvelle heure <span class="text-danger">*</span></label>
                      <input 
                        v-model="eventForm.new_report_time" 
                        type="time" 
                        class="form-control"
                        step="900"
                        required
                      >
                      <small class="text-muted">Intervalles de 15 minutes</small>
                    </div>
                  </div>
                  <small class="text-muted">
                    <i class="fas fa-info-circle"></i>
                    Le rendez-vous sera déplacé à cette nouvelle date et heure.
                  </small>
                </div>
              </div>
              
              <div class="modal-footer">
                <!-- Open Patient File Button -->
                <button 
                  v-if="editingEvent && eventForm.patient_id"
                  type="button"
                  class="btn btn-outline-success me-auto"
                  @click="openPatientFile"
                >
                  <i class="fas fa-folder-open"></i>
                  Ouvrir dossier patient
                </button>

                <button 
                  type="button" 
                  class="btn btn-secondary" 
                  data-bs-dismiss="modal"
                  :disabled="saving"
                >
                  Annuler
                </button>
                <button 
                  v-if="editingEvent && canDelete"
                  type="button"
                  class="btn btn-danger"
                  @click="deleteEvent"
                  :disabled="saving"
                >
                  <i class="fas fa-trash"></i>
                  Supprimer
                </button>
                <button 
                  type="submit"
                  class="btn btn-primary"
                  :disabled="saving"
                >
                  <span v-if="saving">
                    <span class="spinner-border spinner-border-sm me-2"></span>
                    Enregistrement...
                  </span>
                  <span v-else>
                    <i class="fas fa-save"></i>
                    Enregistrer
                  </span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, reactive, watch, defineAsyncComponent } from 'vue'
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'
import listPlugin from '@fullcalendar/list'
import bootstrap5Plugin from '@fullcalendar/bootstrap5'
import resourceTimelinePlugin from '@fullcalendar/resource-timeline'
import frLocale from '@fullcalendar/core/locales/fr'
import axios from 'axios'
import { Modal } from 'bootstrap'
import FlashMessage from './FlashMessage.vue'
import debounce from 'lodash/debounce'

// Props
const props = defineProps({
  medecinId: {
    type: [Number, String],
    required: false
  },
  medecinName: {
    type: String,
    required: false
  },
  editable: {
    type: Boolean,
    default: true
  },
  viewMode: {
    type: String,
    default: 'timeline'
  },
  canCreate: {
    type: Boolean,
    default: false
  },
  canUpdate: {
    type: Boolean,
    default: false
  },
  canDelete: {
    type: Boolean,
    default: false
  },
  userRole: {
    type: Number,
    required: true
  }
})

// Refs
const fullCalendar = ref(null)
const eventModal = ref(null)
const events = ref([])
const patients = ref([])
const allPatients = ref([]) // Store all patients for dropdown

// OPTIMIZATION 1: Lazy load patients on demand
const patientSearchQuery = ref('')
const isLoadingPatients = ref(false)
const patientPage = ref(1)
const hasMorePatients = ref(true)

const medecins = ref([])
const resources = ref([])
const editingEvent = ref(null)
const saving = ref(false)
const loading = ref(true)
const error = ref(null)
const currentView = ref('dayGridMonth')
const calendarTitle = ref('')
const selectedMedecinId = ref(props.medecinId || null)
const showSidebar = computed(() => {
  if (props.userRole === 2) {
    return !props.medecinId
  }
  return (props.userRole === 1 || props.userRole === 6) && !props.medecinId
})
const flashMessage = ref(null)

// Bootstrap Modal instance
let modalInstance = null

// Back URL for medecin view
const backUrl = computed(() => {
  if (props.medecinId) {
    return '/admin/events'
  }
  return '/admin/events'
})

// Setup axios CSRF token
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')


// Event form
const eventForm = reactive({
  patient_id: '',
  medecin_id: props.medecinId || '',
  title: '',
  objet: 'Consultation',
  start_date: '',
  start_time: '09:00',
  end_date: '',
  end_time: '10:00',
  description: '',
  statut: 'a venir',
  original_start_date: '',
  original_start_time: '',
  new_report_date: '',
  new_report_time: ''
})

// Watch patient selection to auto-generate title
watch(() => eventForm.patient_id, (newVal) => {
  if (newVal) {
    const patient = allPatients.value.find(p => p.id == newVal)
    if (patient) {
      eventForm.title = `${patient.name} ${patient.prenom}`
    }
  }
})

// Clear report fields when changing away from "reporté"
watch(() => eventForm.statut, (newStatut) => {
  if (newStatut !== 'reporté') {
    eventForm.new_report_date = ''
    eventForm.new_report_time = ''
  }
})

const updateCalendarTitle = () => {
  const calendarApi = fullCalendar.value?.getApi()
  if (!calendarApi) return

  const currentDate = calendarApi.getDate()
  const viewType = calendarApi.view.type

  const formatMonthYear = new Intl.DateTimeFormat('fr-FR', {
    year: 'numeric',
    month: 'long'
  })

  const formatFullDate = new Intl.DateTimeFormat('fr-FR', {
    weekday: 'long',
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })

  if (viewType === 'dayGridMonth') {
    calendarTitle.value = formatMonthYear.format(currentDate)
  } else if (viewType === 'timeGridWeek' || viewType === 'listWeek') {
    const start = calendarApi.view.activeStart
    const end = calendarApi.view.activeEnd
    const startStr = new Intl.DateTimeFormat('fr-FR', { day: 'numeric', month: 'long' }).format(start)
    const weekEnd = new Date(end)
    weekEnd.setDate(weekEnd.getDate() - 1)
    const endStr = new Intl.DateTimeFormat('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' }).format(weekEnd)
    
    calendarTitle.value = `Semaine du ${startStr} au ${endStr}`
  } else if (viewType === 'timeGridDay') {
    calendarTitle.value = formatFullDate.format(currentDate)
  } else {
    calendarTitle.value = formatMonthYear.format(currentDate)
  }
}

// Get color by status
const getColorByStatut = (statut) => {
  const colors = {
    'a venir': '#4682B4',
    'vu': '#008B8B',
    'absence excusé': '#DDA0DD',
    'absence non excusé': '#6A5ACD',
    'reporté': '#FF6347'
  }
  return colors[statut] || '#3788d8'
}

const selectMedecin = (medecinId) => {
  if (props.userRole === 1 || props.userRole === 6) {
    window.location.href = `/admin/events/medecin/${medecinId}`
  }
}

const navigatePrev = () => {
  const calendarApi = fullCalendar.value?.getApi()
  if (calendarApi) {
    calendarApi.prev()
    updateCalendarTitle()
  }
}

const navigateNext = () => {
  const calendarApi = fullCalendar.value?.getApi()
  if (calendarApi) {
    calendarApi.next()
    updateCalendarTitle()
  }
}

const navigateToday = () => {
  const calendarApi = fullCalendar.value?.getApi()
  if (calendarApi) {
    calendarApi.today()
    updateCalendarTitle()
  }
}

const changeView = (viewName) => {
  const calendarApi = fullCalendar.value?.getApi()
  if (calendarApi) {
    calendarApi.changeView(viewName)
    currentView.value = viewName
    updateCalendarTitle()
  }
}

// Open patient file in new window
const openPatientFile = () => {
  if (eventForm.patient_id) {
    const url = `/admin/patients/${eventForm.patient_id}`
    window.open(url, '_blank')
  }
}


// OPTIMIZATION 2: Debounced patient search
const searchPatients = debounce(async (query) => {
  if (isLoadingPatients.value) return
  
  isLoadingPatients.value = true
  try {
    const response = await axios.get('/admin/api/patients', {
      params: { q: query, page: patientPage.value },
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    
    if (patientPage.value === 1) {
      allPatients.value = response.data
    } else {
      allPatients.value = [...allPatients.value, ...response.data]
    }
    
    hasMorePatients.value = response.data.length === 50
  } catch (err) {
    console.error('Error searching patients:', err)
    flashMessage.value?.error('Erreur lors de la recherche des patients')
  } finally {
    isLoadingPatients.value = false
  }
}, 300)




const calendarOptions = computed(() => {
  const baseOptions = {
    plugins: [
      dayGridPlugin,
      timeGridPlugin,
      interactionPlugin,
      listPlugin,
      bootstrap5Plugin,
      resourceTimelinePlugin
    ],
    themeSystem: 'bootstrap5',
    initialView: 'dayGridMonth',
    headerToolbar: false,
    locale: frLocale,
    locales: [frLocale],
    firstDay: 1,
    slotMinTime: '08:00:00',
    slotMaxTime: '17:00:00',
    slotDuration: '00:15:00',
    scrollTime: '08:00:00',
    aspectRatio: 2,
    resourceAreaWidth: '15%',
    events: events.value,
    resources: resources.value,
    editable: props.editable && (props.canUpdate || props.userRole === 2),
    selectable: props.editable && (props.canCreate || props.userRole === 2),
    selectAllow: (selectInfo) => {
      const now = new Date()
      return selectInfo.start >= now
    },
    select: handleDateSelect,
    eventClick: handleEventClick,
    eventDrop: handleEventDrop,
    eventResize: handleEventResize,
    businessHours: [
      {
        daysOfWeek: [1, 2, 3, 4, 5],
        startTime: '08:00',
        endTime: '13:00'
      },
      {
        daysOfWeek: [1, 2, 3, 4, 5],
        startTime: '14:00',
        endTime: '17:00'
      }
    ],
    height: 'auto',
    nowIndicator: true,
    eventTimeFormat: {
      hour: '2-digit',
      minute: '2-digit',
      hour12: false
    },
    resourceLabelText: 'Médecins',
    contentHeight: 'auto',
  }

  return baseOptions
})

// Load all patients for the dropdown
const loadAllPatients = async () => {
  // if (allPatients.value.length > 0) return // Already loaded
  
  // try {
  //   const response = await axios.get('/admin/api/patients', {
  //     headers: {
  //       'Accept': 'application/json',
  //       'X-Requested-With': 'XMLHttpRequest'
  //     }
  //   })
    
  //   allPatients.value = Array.isArray(response.data) ? response.data : []
  // } catch (err) {
  //   console.error('Error loading all patients:', err)
  //   flashMessage.value?.error('Erreur lors du chargement des patients')
  // }


   if (allPatients.value.length > 0) return // Already loaded
  
  patientPage.value = 1
  await searchPatients('')

}


// OPTIMIZATION 4: Infinite scroll for patient dropdown
const handlePatientScroll = (event) => {
  const { scrollTop, scrollHeight, clientHeight } = event.target
  
  if (scrollTop + clientHeight >= scrollHeight - 10 && 
      hasMorePatients.value && 
      !isLoadingPatients.value) {
    patientPage.value++
    searchPatients(patientSearchQuery.value)
  }
}




// OPTIMIZATION 5: Watch search input
watch(patientSearchQuery, (newQuery) => {
  patientPage.value = 1
  searchPatients(newQuery)
})


// OPTIMIZATION 6: Optimized event loading with progress indication
const loadEvents = async () => {
  try {
    loading.value = true
    error.value = null

    const url = props.medecinId
      ? `/admin/events/medecin/${props.medecinId}`
      : '/admin/events'

    const response = await axios.get(url, {
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      // Add timeout
      timeout: 10000
    })

    if (!response.data || !Array.isArray(response.data)) {
      throw new Error('Invalid response format')
    }

    // Process events in chunks for better performance
    const chunkSize = 100
    const chunks = []
    for (let i = 0; i < response.data.length; i += chunkSize) {
      chunks.push(response.data.slice(i, i + chunkSize))
    }

    events.value = []
    for (const chunk of chunks) {
      const processedChunk = chunk.map(event => ({
        id: event.id,
        title: event.title,
        start: event.start,
        end: event.end,
        resourceId: event.medecin_id?.toString(),
        backgroundColor: event.color || getColorByStatut(event.statut || 'a venir'),
        borderColor: event.color || getColorByStatut(event.statut || 'a venir'),
        extendedProps: {
          patient_id: event.patient_id,
          medecin_id: event.medecin_id,
          description: event.description || '',
          objet: event.objet || '',
          statut: event.statut || 'a venir',
          patient: event.patient,
          medecin: event.medecin
        }
      }))
      
      events.value = [...events.value, ...processedChunk]
      
      // Allow UI to update between chunks
      await new Promise(resolve => setTimeout(resolve, 0))
    }

  } catch (err) {
    console.error('Error loading events:', err)
    error.value = 'Erreur lors du chargement des événements. ' + 
                  (err.response?.data?.message || err.message)
    flashMessage.value?.error('Erreur lors du chargement des événements')
  } finally {
    loading.value = false
  }
}


// Load patients
const loadPatients = async () => {
  try {
    const response = await axios.get('/admin/api/patients', {
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    
    patients.value = Array.isArray(response.data) ? response.data : []
    allPatients.value = patients.value // Initialize allPatients with full list
    
  } catch (err) {
    console.error('Error loading patients:', err)
    error.value = 'Erreur lors du chargement des patients'
    flashMessage.value?.error('Erreur lors du chargement des patients')
  }
}

// Load medecins
const loadMedecins = async () => {
  try {
    const response = await axios.get('/admin/api/medecins', {
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    
    let filteredMedecins = Array.isArray(response.data) ? response.data : []
    
    if (props.userRole === 2 && props.medecinId) {
      filteredMedecins = filteredMedecins.filter(m => m.id == props.medecinId)
    }
    
    medecins.value = filteredMedecins
    
    resources.value = medecins.value.map(doc => ({
      id: doc.id.toString(),
      title: `Dr. ${doc.name} ${doc.prenom || ''}`
    }))
    
  } catch (err) {
    console.error('Error loading medecins:', err)
    error.value = 'Erreur lors du chargement des médecins'
    flashMessage.value?.error('Erreur lors du chargement des médecins')
  }
}

const handleDateSelect = (selectInfo) => {
  if (!props.canCreate && props.userRole !== 2) return

  const selectedDateTime = selectInfo.start
  const now = new Date()

  editingEvent.value = null
  eventForm.patient_id = ''
  eventForm.medecin_id = props.medecinId || ''
  eventForm.title = ''
  eventForm.objet = 'Consultation'
  eventForm.description = ''
  eventForm.statut = 'a venir'
  eventForm.original_start_date = ''
  eventForm.original_start_time = ''
  eventForm.new_report_date = ''
  eventForm.new_report_time = ''

  if (selectInfo.allDay) {
    const selectedDate = new Date(selectedDateTime)
    selectedDate.setHours(0, 0, 0, 0)
    const today = new Date()
    today.setHours(0, 0, 0, 0)

    if (selectedDate < today) {
      flashMessage.value?.warning('Vous ne pouvez pas créer un rendez-vous pour une date passée.')
      return
    }
    
    const clickedDate = selectInfo.startStr.split('T')[0]
    eventForm.start_date = clickedDate
    eventForm.end_date = clickedDate
    eventForm.start_time = '09:00'
    eventForm.end_time = '10:00'
  } else {
    if (selectedDateTime < now) {
      flashMessage.value?.warning('Vous ne pouvez pas créer un rendez-vous pour une date/heure passée.')
      return
    }

    eventForm.start_date = selectInfo.startStr.split('T')[0]
    const startTime = selectInfo.start.toTimeString().slice(0, 5)
    
    let endTime
    if (selectInfo.end) {
      endTime = selectInfo.end.toTimeString().slice(0, 5)
      eventForm.end_date = selectInfo.endStr ? selectInfo.endStr.split('T')[0] : eventForm.start_date
    } else {
      const endDate = new Date(selectInfo.start)
      endDate.setHours(endDate.getHours() + 1)
      endTime = endDate.toTimeString().slice(0, 5)
      eventForm.end_date = eventForm.start_date
    }
    
    eventForm.start_time = startTime
    eventForm.end_time = endTime
  }

  if (selectInfo.resource) {
    eventForm.medecin_id = selectInfo.resource.id
  }

  openModal()

  const calendarApi = fullCalendar.value?.getApi()
  if (calendarApi) {
    calendarApi.unselect()
  }
}

const handleEventClick = (clickInfo) => {
  const event = clickInfo.event
  editingEvent.value = event
  
  eventForm.patient_id = event.extendedProps.patient_id
  eventForm.medecin_id = event.extendedProps.medecin_id
  eventForm.title = event.title
  eventForm.objet = event.extendedProps.objet || 'Consultation'
  eventForm.start_date = event.startStr.split('T')[0]
  eventForm.start_time = event.start ? event.start.toTimeString().slice(0, 5) : '09:00'
  eventForm.end_date = event.endStr ? event.endStr.split('T')[0] : eventForm.start_date
  eventForm.end_time = event.end ? event.end.toTimeString().slice(0, 5) : '10:00'
  eventForm.description = event.extendedProps.description || ''
  eventForm.statut = event.extendedProps.statut || 'a venir'
  
  eventForm.original_start_date = event.startStr.split('T')[0]
  eventForm.original_start_time = event.start ? event.start.toTimeString().slice(0, 5) : '09:00'
  
  openModal()
}

const handleEventDrop = async (dropInfo) => {
  if (!props.editable && props.userRole !== 2) {
    dropInfo.revert()
    return
  }
  
  try {
    const event = dropInfo.event
    const updateData = {
      start: event.startStr,
      end: event.endStr
    }
    
    if (event.getResources && event.getResources().length > 0) {
      updateData.medecin_id = event.getResources()[0].id
    }
    
    await axios.put(`/admin/events/${event.id}`, updateData)
    
    event.setExtendedProp('medecin_id', updateData.medecin_id)
    flashMessage.value?.success('Rendez-vous déplacé avec succès')
  } catch (err) {
    console.error('Error updating event:', err)
    dropInfo.revert()
    flashMessage.value?.error(err.response?.data?.message || 'Erreur lors de la mise à jour du rendez-vous')
  }
}

const handleEventResize = async (resizeInfo) => {
  if (!props.editable && props.userRole !== 2) {
    resizeInfo.revert()
    return
  }
  
  try {
    await axios.put(`/admin/events/${resizeInfo.event.id}`, {
      start: resizeInfo.event.startStr,
      end: resizeInfo.event.endStr
    })
    flashMessage.value?.success('Durée du rendez-vous modifiée avec succès')
  } catch (err) {
    console.error('Error resizing event:', err)
    resizeInfo.revert()
    flashMessage.value?.error('Erreur lors de la modification de la durée')
  }
}

const openCreateModal = () => {
  editingEvent.value = null
  resetForm()
  openModal()
}

const openModal = () => {
  if (!modalInstance) {
    modalInstance = new Modal(eventModal.value)
  }
  modalInstance.show()
}

const closeModal = () => {
  if (modalInstance) {
    modalInstance.hide()
  }
}

const resetForm = () => {
  eventForm.patient_id = ''
  eventForm.medecin_id = props.medecinId || ''
  eventForm.title = ''
  eventForm.objet = 'Consultation'
  eventForm.start_date = ''
  eventForm.start_time = '09:00'
  eventForm.end_date = ''
  eventForm.end_time = '10:00'
  eventForm.description = ''
  eventForm.statut = 'a venir'
  eventForm.original_start_date = ''
  eventForm.original_start_time = ''
  eventForm.new_report_date = ''
  eventForm.new_report_time = ''
}

const saveEvent = async () => {
  if (editingEvent.value && !props.canUpdate && props.userRole !== 2) {
    flashMessage.value?.warning('Vous n\'avez pas la permission de modifier ce rendez-vous.')
    return
  }
  
  if (!editingEvent.value && !props.canCreate && props.userRole !== 2) {
    flashMessage.value?.warning('Vous n\'avez pas la permission de créer des rendez-vous.')
    return
  }

  if (!eventForm.patient_id) {
    flashMessage.value?.warning('Veuillez sélectionner un patient')
    return
  }
  
  if (!eventForm.medecin_id && !props.medecinId) {
    flashMessage.value?.warning('Veuillez sélectionner un médecin')
    return
  }
  
  if (!eventForm.objet) {
    flashMessage.value?.warning('Veuillez sélectionner un objet')
    return
  }

  if (eventForm.statut === 'reporté') {
    if (!eventForm.new_report_date || !eventForm.new_report_time) {
      flashMessage.value?.warning('Veuillez choisir une nouvelle date et heure pour le report.')
      return
    }
    
    const newDateTime = new Date(`${eventForm.new_report_date}T${eventForm.new_report_time}`)
    const now = new Date()
    if (newDateTime < now) {
      flashMessage.value?.warning('La nouvelle date de report ne peut pas être dans le passé.')
      return
    }
  }
  
  if (!editingEvent.value) {
    const startDateTime = new Date(`${eventForm.start_date}T${eventForm.start_time}`)
    const now = new Date()
    if (startDateTime < now) {
      flashMessage.value?.warning('Vous ne pouvez pas créer un rendez-vous pour une date/heure passée.')
      return
    }
  }
  
  const calendarApi = fullCalendar.value?.getApi()
  const previousViewType = calendarApi?.view.type
  const previousDate = calendarApi ? calendarApi.getDate() : null
  
  saving.value = true
  
  try {
    const patient = allPatients.value.find(p => p.id == eventForm.patient_id)
    const title = patient ? `${patient.name} ${patient.prenom}` : eventForm.title
    
    let startDateTime, endDateTime
    if (eventForm.statut === 'reporté' && eventForm.new_report_date && eventForm.new_report_time) {
    startDateTime = `${eventForm.new_report_date}T${eventForm.new_report_time}:00`

    const endDate = new Date(`${eventForm.new_report_date}T${eventForm.new_report_time}`)
    endDate.setHours(endDate.getHours() + 1)

    // Correct formatting: keep full ISO up to seconds
    endDateTime = endDate.toISOString().slice(0, 19) // "YYYY-MM-DDTHH:mm:ss"
  } else {
    startDateTime = `${eventForm.start_date}T${eventForm.start_time}:00`
    endDateTime = `${eventForm.end_date || eventForm.start_date}T${eventForm.end_time}:00`
  }


    const eventData = {
      patient_id: eventForm.patient_id,
      medecin_id: eventForm.medecin_id || props.medecinId,
      title: title,
      objet: eventForm.objet,
      start: startDateTime,
      end: endDateTime,
      description: eventForm.description,
      statut: eventForm.statut
    }
    
    if (editingEvent.value) {
      const response = await axios.put(`/admin/events/${editingEvent.value.id}`, eventData)
      
      editingEvent.value.setProp('title', response.data.event.title)
      editingEvent.value.setStart(response.data.event.start)
      editingEvent.value.setEnd(response.data.event.end)
      editingEvent.value.setProp('backgroundColor', response.data.event.color || getColorByStatut(response.data.event.statut))
      editingEvent.value.setProp('borderColor', response.data.event.color || getColorByStatut(response.data.event.statut))
      editingEvent.value.setExtendedProp('description', response.data.event.description)
      editingEvent.value.setExtendedProp('objet', response.data.event.objet)
      editingEvent.value.setExtendedProp('statut', response.data.event.statut)
      editingEvent.value.setExtendedProp('patient_id', response.data.event.patient_id)
      editingEvent.value.setExtendedProp('medecin_id', response.data.event.medecin_id)
      
      if (response.data.event.medecin_id) {
        editingEvent.value.setResources([response.data.event.medecin_id.toString()])
      }
      
      await loadEvents() 
      flashMessage.value?.success(response.data.message || 'Rendez-vous modifié avec succès')
    } else {
      const response = await axios.post('/admin/events', eventData)
      await loadEvents()
      flashMessage.value?.success(response.data.message || 'Rendez-vous créé avec succès')
    }
    
    closeModal()
    resetForm()
    
    if (calendarApi && previousViewType) {
      calendarApi.changeView(previousViewType, previousDate || undefined)
      currentView.value = previousViewType
      updateCalendarTitle()
    }
    
  } catch (err) {
    console.error('Error saving event:', err)
    const errorMsg = err.response?.data?.message || err.response?.data?.errors || 'Erreur lors de l\'enregistrement'
    const displayMsg = typeof errorMsg === 'object' ? JSON.stringify(errorMsg) : errorMsg 
    flashMessage.value?.error(displayMsg)
  } finally {
    saving.value = false
  }
}

const deleteEvent = async () => {
  if (!props.canDelete && props.userRole !== 2) {
    flashMessage.value?.warning('Vous n\'avez pas la permission de supprimer ce rendez-vous.')
    return
  }

  if (!confirm('Êtes-vous sûr de vouloir supprimer ce rendez-vous ?')) {
    return
  }

  const calendarApi = fullCalendar.value?.getApi()
  const previousViewType = calendarApi?.view.type
  const previousDate = calendarApi ? calendarApi.getDate() : null
  
  saving.value = true
  
  try {
    await axios.delete(`/admin/events/${editingEvent.value.id}`)
    editingEvent.value.remove()
    await loadEvents()
    
    if (calendarApi && previousViewType) {
      calendarApi.changeView(previousViewType, previousDate || undefined)
      currentView.value = previousViewType
      updateCalendarTitle()
    }
    
    closeModal()
    resetForm()
    flashMessage.value?.success('Rendez-vous supprimé avec succès')
  } catch (err) {
    console.error('Error deleting event:', err)
    flashMessage.value?.error('Erreur lors de la suppression')
  } finally {
    saving.value = false
  }
}

// OPTIMIZATION 7: Don't load patients on mount, only when needed
onMounted(async () => {
  try {
    await loadMedecins()
    // Remove loadPatients() - load on demand instead
    await loadEvents()
    
    const calendarApi = fullCalendar.value?.getApi()
    if (calendarApi) {
      calendarApi.today()
      currentView.value = 'dayGridMonth'
      updateCalendarTitle()
    }
  } catch (err) {
    console.error('Error during initialization:', err)
    error.value = 'Erreur lors de l\'initialisation du calendrier'
    loading.value = false
  }
  updateCalendarTitle()
})

// Export the component methods
defineExpose({
  loadEvents,
  getApi: () => fullCalendar.value?.getApi()
})

</script>

<style scoped>


/* Status Button Colors */
:root {
  --bs-steelblue: #4682B4;
  --bs-darkcyan: #008B8B;
  --bs-plum: #DDA0DD;
  --bs-slateblue: #6A5ACD;
  --bs-tomato: #FF6347;
}

.btn-steelblue {
  --bs-btn-color: #fff;
  --bs-btn-bg: var(--bs-steelblue);
  --bs-btn-border-color: var(--bs-steelblue);
  --bs-btn-hover-color: #fff;
  --bs-btn-hover-bg: #3a6d9c;
  --bs-btn-hover-border-color: #335f89;
  --bs-btn-active-color: #fff;
  --bs-btn-active-bg: #335f89;
  --bs-btn-active-border-color: #2c5276;
  --bs-btn-disabled-color: #fff;
  --bs-btn-disabled-bg: var(--bs-steelblue);
  --bs-btn-disabled-border-color: var(--bs-steelblue);
}

.btn-darkcyan {
  --bs-btn-color: #fff;
  --bs-btn-bg: var(--bs-darkcyan);
  --bs-btn-border-color: var(--bs-darkcyan);
  --bs-btn-hover-color: #fff;
  --bs-btn-hover-bg: #007070;
  --bs-btn-hover-border-color: #006363;
  --bs-btn-active-color: #fff;
  --bs-btn-active-bg: #006363;
  --bs-btn-active-border-color: #005656;
  --bs-btn-disabled-color: #fff;
  --bs-btn-disabled-bg: var(--bs-darkcyan);
  --bs-btn-disabled-border-color: var(--bs-darkcyan);
}

.btn-plum {
  --bs-btn-color: #000;
  --bs-btn-bg: var(--bs-plum);
  --bs-btn-border-color: var(--bs-plum);
  --bs-btn-hover-color: #000;
  --bs-btn-hover-bg: #d08fd0;
  --bs-btn-hover-border-color: #c87fc8;
  --bs-btn-active-color: #000;
  --bs-btn-active-bg: #c87fc8;
  --bs-btn-active-border-color: #bf6fbf;
  --bs-btn-disabled-color: #000;
  --bs-btn-disabled-bg: var(--bs-plum);
  --bs-btn-disabled-border-color: var(--bs-plum);
}

.btn-slateblue {
  --bs-btn-color: #fff;
  --bs-btn-bg: var(--bs-slateblue);
  --bs-btn-border-color: var(--bs-slateblue);
  --bs-btn-hover-color: #fff;
  --bs-btn-hover-bg: #5d4eb7;
  --bs-btn-hover-border-color: #5546a8;
  --bs-btn-active-color: #fff;
  --bs-btn-active-bg: #5546a8;
  --bs-btn-active-border-color: #4c3f99;
  --bs-btn-disabled-color: #fff;
  --bs-btn-disabled-bg: var(--bs-slateblue);
  --bs-btn-disabled-border-color: var(--bs-slateblue);
}

.btn-tomato {
  --bs-btn-color: #fff;
  --bs-btn-bg: var(--bs-tomato);
  --bs-btn-border-color: var(--bs-tomato);
  --bs-btn-hover-color: #fff;
  --bs-btn-hover-bg: #e0553b;
  --bs-btn-hover-border-color: #d44d32;
  --bs-btn-active-color: #fff;
  --bs-btn-active-bg: #d44d32;
  --bs-btn-active-border-color: #c0442d;
  --bs-btn-disabled-color: #fff;
  --bs-btn-disabled-bg: var(--bs-tomato);
  --bs-btn-disabled-border-color: var(--bs-tomato);
}

.status-btn {
  padding: 0.375rem 0.75rem;
  font-size: 0.875rem;
  text-transform: none;
}


.calendar-skeleton {
  padding: 20px;
  background: white;
  border-radius: 8px;
}

.skeleton-header {
  height: 40px;
  background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
  background-size: 200% 100%;
  animation: loading 1.5s infinite;
  border-radius: 4px;
  margin-bottom: 20px;
}

.skeleton-grid {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  gap: 10px;
}

.skeleton-cell {
  height: 100px;
  background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
  background-size: 200% 100%;
  animation: loading 1.5s infinite;
  border-radius: 4px;
}

@keyframes loading {
  0% { background-position: 200% 0; }
  100% { background-position: -200% 0; }
}


.patient-select {
  max-height: 300px;
  overflow-y: auto;
}

.patient-select::-webkit-scrollbar {
  width: 8px;
}

.patient-select::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}

.patient-select::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 4px;
}

.patient-select::-webkit-scrollbar-thumb:hover {
  background: #555;
}



.calendar-wrapper {
  display: flex;
  padding: 20px;
  align-items: flex-start;
  min-height: calc(100vh - 40px);
}

.content-row {
  display: flex;
  gap: 20px;
  align-items: flex-start;
}

.content-row.with-sidebar {
  gap: 20px;
}

.medecin-sidebar {
  width: 250px;
  background: white;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  display: flex;
  flex-direction: column;
  align-self: flex-start;
  max-height: calc(100vh - 40px);
  overflow: hidden;
}

.sidebar-title {
  font-weight: 600;
  margin-bottom: 15px;
  padding-bottom: 10px;
  border-bottom: 2px solid #e9ecef;
  flex-shrink: 0;
}

.medecin-list {
  display: flex;
  flex-direction: column;
  gap: 10px;
  overflow-y: auto;
  flex: 1;
  padding-right: 5px;
}

.medecin-list::-webkit-scrollbar {
  width: 6px;
}

.medecin-list::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 3px;
}

.medecin-list::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 3px;
}

.medecin-list::-webkit-scrollbar-thumb:hover {
  background: #555;
}

.medecin-item {
  padding: 12px;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.2s;
  border: 1px solid #e9ecef;
}

.medecin-item:hover {
  background-color: #f8f9fa;
  border-color: #0d6efd;
}

.medecin-item.active {
  background-color: #0d6efd;
  color: white;
  border-color: #0d6efd;
}

.calendar-area {
  flex: 1;
  display: flex;
  flex-direction: column;
  min-height: 0;
}

.calendar-area.with-sidebar {
  flex: 1;
}

.calendar-container {
  background: white;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  min-height: 600px;
  height: auto;
}

:deep(.fc) {
  font-family: inherit;
}

:deep(.fc-event) {
  cursor: pointer;
  border-radius: 4px;
  padding: 2px 5px;
  transition: opacity 0.2s;
}

:deep(.fc-event:hover) {
  opacity: 0.85;
}

:deep(.fc-toolbar-title) {
  font-size: 1.75rem;
  font-weight: 600;
  color: #2c3e50;
}

:deep(.fc-button) {
  text-transform: capitalize;
  padding: 0.5rem 1rem;
}

:deep(.fc-daygrid-day-number) {
  padding: 8px;
  font-weight: 500;
}

:deep(.fc-daygrid-day) {
  min-height: 120px;
}

:deep(.fc-daygrid-body) {
  width: 100% !important;
}

:deep(.fc .fc-daygrid-day-frame) {
  min-height: 120px;
}

:deep(.fc-col-header-cell) {
  background-color: #f8f9fa;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 0.85rem;
  padding: 12px 0;
}

:deep(.fc-scrollgrid) {
  border-radius: 8px;
  overflow: hidden;
}

:deep(.fc-scrollgrid-sync-table) {
  height: 100%;
}

/* Status button colors matching backend */
.btn-check:checked + .status-avenir {
  background-color: #4682B4 !important;
  border-color: #4682B4 !important;
  color: white !important;
}

.btn-check:checked + .status-vu {
  background-color: #008B8B !important;
  border-color: #008B8B !important;
  color: white !important;
}

.btn-check:checked + .status-excuse {
  background-color: #DDA0DD !important;
  border-color: #DDA0DD !important;
  color: white !important;
}

.btn-check:checked + .status-non-excuse {
  background-color: #6A5ACD !important;
  border-color: #6A5ACD !important;
  color: white !important;
}

.btn-check:checked + .status-reporte {
  background-color: #FF6347 !important;
  border-color: #FF6347 !important;
  color: white !important;
}

.btn-group .btn {
  padding: 0.5rem 1rem;
}

.btn-group .btn.active {
  background-color: #0d6efd;
  color: white;
  border-color: #0d6efd;
}

.btn-outline-secondary:hover {
  background-color: #6c757d;
  color: white;
}

.calendar-header .btn-group {
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

/* Patient select dropdown with better scrolling */
.form-select {
  max-height: 200px;
  overflow-y: auto;
  
}



.status-legend {
  padding: 10px 0;
}
.legend-item {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 0.875rem;
}
.legend-color {
  width: 12px;
  height: 12px;
  border-radius: 2px;
}
.legend-label {
  color: #495057;
}

/* Status Colors */
.bg-steelblue { background-color: #4682B4 !important; }
.bg-darkcyan { background-color: #008B8B !important; }
.bg-plum { background-color: #DDA0DD !important; }
.bg-slateblue { background-color: #6A5ACD !important; }
.bg-tomato { background-color: #FF6347 !important; }





</style>


