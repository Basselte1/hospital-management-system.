
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
                    class="form-select patient-select"
                    required
                    @scroll="handlePatientScroll"
                    @focus="loadAllPatients"
                  >
                    <option value="">Sélectionner un patient</option>
                    <option 
                      v-for="patient in filteredPatients" 
                      :key="patient.id" 
                      :value="patient.id"
                    >
                      {{ patient.name }} {{ patient.prenom }}
                    </option>
                    <option v-if="isLoadingPatients" disabled>
                      Chargement...
                    </option>
                  </select>
                  <small class="text-muted d-block mt-1">
                    <i class="fas fa-search"></i>
                    Tapez pour rechercher ou faites défiler pour charger plus
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
                  <div class="d-flex gap-3 flex-wrap">
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
                  <label class="form-label fw-bold mb-3">Statut</label>
                  <div class="status-button-group">
                    <!-- Always available statuses -->
                    <div class="status-button-wrapper">
                      <input 
                        type="radio" 
                        class="btn-check" 
                        v-model="eventForm.statut"
                        value="a venir"
                        id="statutAvenir"
                      >
                      <label class="btn btn-outline-status btn-outline-steelblue" for="statutAvenir">
                        <i class="fas fa-clock me-1"></i>
                        À venir
                      </label>
                    </div>
                    
                    <div class="status-button-wrapper">
                      <input 
                        type="radio" 
                        class="btn-check" 
                        v-model="eventForm.statut"
                        value="vu"
                        id="statutVu"
                      >
                      <label class="btn btn-outline-status btn-outline-darkcyan" for="statutVu">
                        <i class="fas fa-check-circle me-1"></i>
                        Vu
                      </label>
                    </div>
                    
                    <!-- Restricted statuses for medecins -->
                    <template v-if="userRole !== 2">
                      <div class="status-button-wrapper">
                        <input 
                          type="radio" 
                          class="btn-check" 
                          v-model="eventForm.statut"
                          value="absence excusé"
                          id="statutExcuse"
                        >
                        <label class="btn btn-outline-status btn-outline-plum" for="statutExcuse">
                          <i class="fas fa-user-check me-1"></i>
                          Absence excusée
                        </label>
                      </div>
                      
                      <div class="status-button-wrapper">
                        <input 
                          type="radio" 
                          class="btn-check" 
                          v-model="eventForm.statut"
                          value="absence non excusé"
                          id="statutNonExcuse"
                        >
                        <label class="btn btn-outline-status btn-outline-slateblue" for="statutNonExcuse">
                          <i class="fas fa-user-times me-1"></i>
                          Absence non excusée
                        </label>
                      </div>
                    </template>
                    
                    <div class="status-button-wrapper">
                      <input 
                        type="radio" 
                        class="btn-check" 
                        v-model="eventForm.statut"
                        value="reporté"
                        id="statutReporte"
                      >
                      <label class="btn btn-outline-status btn-outline-tomato" for="statutReporte">
                        <i class="fas fa-calendar-alt me-1"></i>
                        Reporté
                      </label>
                    </div>
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
import { ref, computed, onMounted, reactive, watch } from 'vue'
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

// ==================== PROPS ====================
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

// ==================== REFS ====================
const fullCalendar = ref(null)
const eventModal = ref(null)
const flashMessage = ref(null)
const events = ref([])
const patients = ref([])
const allPatients = ref([])
const medecins = ref([])
const resources = ref([])
const editingEvent = ref(null)
const saving = ref(false)
const loading = ref(true)
const error = ref(null)
const currentView = ref('dayGridMonth')
const calendarTitle = ref('')
const selectedMedecinId = ref(props.medecinId || null)

// Patient search optimization
const patientSearchQuery = ref('')
const isLoadingPatients = ref(false)
const patientPage = ref(1)
const hasMorePatients = ref(true)

// Bootstrap Modal instance
let modalInstance = null

// ==================== COMPUTED ====================
const showSidebar = computed(() => {
  if (props.userRole === 2) {
    return !props.medecinId
  }
  return (props.userRole === 1 || props.userRole === 6) && !props.medecinId
})

const backUrl = computed(() => {
  if (props.medecinId) {
    return '/admin/events'
  }
  return '/admin/events'
})

const filteredPatients = computed(() => {
  if (!patientSearchQuery.value) {
    return allPatients.value
  }
  
  const query = patientSearchQuery.value.toLowerCase()
  return allPatients.value.filter(patient => {
    const fullName = `${patient.name} ${patient.prenom}`.toLowerCase()
    return fullName.includes(query)
  })
})

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

// ==================== REACTIVE FORM ====================
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

// ==================== AXIOS SETUP ====================
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')

// ==================== WATCHERS ====================
watch(() => eventForm.patient_id, (newVal) => {
  if (newVal) {
    const patient = allPatients.value.find(p => p.id == newVal)
    if (patient) {
      eventForm.title = `${patient.name} ${patient.prenom}`
    }
  }
})

watch(() => eventForm.statut, (newStatut) => {
  if (newStatut !== 'reporté') {
    eventForm.new_report_date = ''
    eventForm.new_report_time = ''
  }
})

watch(patientSearchQuery, (newQuery) => {
  patientPage.value = 1
  searchPatients(newQuery)
})

// ==================== UTILITY FUNCTIONS ====================
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

// ==================== NAVIGATION FUNCTIONS ====================
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

const selectMedecin = (medecinId) => {
  if (props.userRole === 1 || props.userRole === 6) {
    window.location.href = `/admin/events/medecin/${medecinId}`
  }
}

const openPatientFile = () => {
  if (eventForm.patient_id) {
    const url = `/admin/patients/${eventForm.patient_id}`
    window.open(url, '_blank')
  }
}

// ==================== DATA LOADING FUNCTIONS ====================
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

const loadAllPatients = async () => {
  if (allPatients.value.length > 0) return
  
  patientPage.value = 1
  await searchPatients('')
}

const handlePatientScroll = (event) => {
  const { scrollTop, scrollHeight, clientHeight } = event.target
  
  if (scrollTop + clientHeight >= scrollHeight - 10 && 
      hasMorePatients.value && 
      !isLoadingPatients.value) {
    patientPage.value++
    searchPatients(patientSearchQuery.value)
  }
}

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
      timeout: 10000
    })

    if (!response.data || !Array.isArray(response.data)) {
      throw new Error('Invalid response format')
    }

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

const loadPatients = async () => {
  try {
    const response = await axios.get('/admin/api/patients', {
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    
    patients.value = Array.isArray(response.data) ? response.data : []
    allPatients.value = patients.value
    
  } catch (err) {
    console.error('Error loading patients:', err)
    error.value = 'Erreur lors du chargement des patients'
    flashMessage.value?.error('Erreur lors du chargement des patients')
  }
}

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

// ==================== EVENT HANDLERS ====================
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

// ==================== MODAL FUNCTIONS ====================
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

// ==================== CRUD OPERATIONS ====================
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
      endDateTime = endDate.toISOString().slice(0, 16) + ':00'
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

// ==================== LIFECYCLE ====================
onMounted(async () => {
  try {
    await loadMedecins()
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

// ==================== EXPOSE ====================
defineExpose({
  loadEvents,
  getApi: () => fullCalendar.value?.getApi()
})

</script>

<style scoped>
/* ==================== CSS VARIABLES ==================== */
:root {
  --color-steelblue: #4682B4;
  --color-darkcyan: #008B8B;
  --color-plum: #DDA0DD;
  --color-slateblue: #6A5ACD;
  --color-tomato: #FF6347;
}

/* ==================== LAYOUT ==================== */
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

/* ==================== SIDEBAR ==================== */
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

/* ==================== LOADING SKELETON ==================== */
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

/* ==================== PATIENT SELECT ==================== */
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

/* ==================== STATUS LEGEND ==================== */
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

.bg-steelblue { background-color: var(--color-steelblue) !important; }
.bg-darkcyan { background-color: var(--color-darkcyan) !important; }
.bg-plum { background-color: var(--color-plum) !important; }
.bg-slateblue { background-color: var(--color-slateblue) !important; }
.bg-tomato { background-color: var(--color-tomato) !important; }

/* ==================== STATUS BUTTONS (FIXED) ==================== */
.status-button-group {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.status-button-wrapper {
  flex: 1;
  min-width: 150px;
}

/* Outline button base styles */
.btn-outline-status {
  width: 100%;
  padding: 12px 16px;
  font-size: 0.9rem;
  font-weight: 500;
  border-width: 2px;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Steelblue (À venir) */
.btn-outline-steelblue {
  color: var(--color-steelblue);
  border-color: var(--color-steelblue);
  background-color: transparent;
}

.btn-outline-steelblue:hover {
  background-color: rgba(70, 130, 180, 0.1);
}

.btn-check:checked + .btn-outline-steelblue {
  background-color: var(--color-steelblue) !important;
  border-color: var(--color-steelblue) !important;
  color: white !important;
}

/* Darkcyan (Vu) */
.btn-outline-darkcyan {
  color: var(--color-darkcyan);
  border-color: var(--color-darkcyan);
  background-color: transparent;
}

.btn-outline-darkcyan:hover {
  background-color: rgba(0, 139, 139, 0.1);
}

.btn-check:checked + .btn-outline-darkcyan {
  background-color: var(--color-darkcyan) !important;
  border-color: var(--color-darkcyan) !important;
  color: white !important;
}

/* Plum (Absence excusée) */
.btn-outline-plum {
  color: var(--color-plum);
  border-color: var(--color-plum);
  background-color: transparent;
}

.btn-outline-plum:hover {
  background-color: rgba(221, 160, 221, 0.2);
}

.btn-check:checked + .btn-outline-plum {
  background-color: var(--color-plum) !important;
  border-color: var(--color-plum) !important;
  color: #333 !important;
}

/* Slateblue (Absence non excusée) */
.btn-outline-slateblue {
  color: var(--color-slateblue);
  border-color: var(--color-slateblue);
  background-color: transparent;
}

.btn-outline-slateblue:hover {
  background-color: rgba(106, 90, 205, 0.1);
}

.btn-check:checked + .btn-outline-slateblue {
  background-color: var(--color-slateblue) !important;
  border-color: var(--color-slateblue) !important;
  color: white !important;
}

/* Tomato (Reporté) */
.btn-outline-tomato {
  color: var(--color-tomato);
  border-color: var(--color-tomato);
  background-color: transparent;
}

.btn-outline-tomato:hover {
  background-color: rgba(255, 99, 71, 0.1);
}

.btn-check:checked + .btn-outline-tomato {
  background-color: var(--color-tomato) !important;
  border-color: var(--color-tomato) !important;
  color: white !important;
}

/* Additional styles for better spacing in modal */
.modal-body {
  padding: 20px;
}

/* Form labels */
.form-label {
  font-weight: bold;
}

/* Button styles for modal footer */
.modal-footer {
  display: flex;
  justify-content: space-between;
  padding: 15px;
}
</style>