<template>
  <!-- Root: fills the <main> column from the blade layout -->
  <div class="tw-flex tw-flex-col tw-gap-5">

    <!-- Flash Messages -->
    <flash-message ref="flashMessage"></flash-message>

    <!-- ═══════════════════════════════════════════════════════
         HERO HEADER
    ════════════════════════════════════════════════════════ -->
    <div class="tw-rounded-2xl tw-bg-gradient-to-r tw-from-[#1D4ED8] tw-to-[#2563eb] tw-px-6 tw-py-5 tw-shadow-sm">
      <div class="tw-flex tw-items-center tw-justify-between tw-flex-wrap tw-gap-4">

        <!-- Title block -->
        <div class="tw-flex tw-items-center tw-gap-3">
          <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-white/20 tw-flex tw-items-center tw-justify-center tw-shrink-0">
            <i class="fas fa-calendar-alt tw-text-white"></i>
          </div>
          <div>
            <h1 class="tw-text-lg tw-font-bold tw-text-white tw-mb-0 tw-leading-tight">
              {{ medecinName ? `Agenda — Dr ${medecinName}` : 'Calendrier des Rendez-vous' }}
            </h1>
            <p class="tw-text-[#BFDBFE] tw-text-xs tw-mb-0 tw-mt-0.5">Gestion des rendez-vous patients</p>
          </div>
        </div>

        <!-- Action buttons -->
        <div class="tw-flex tw-items-center tw-gap-2 tw-flex-wrap">
          <a
            v-if="medecinId && userRole !== 2"
            :href="backUrl"
            class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-border tw-border-white/30 tw-text-white hover:tw-bg-white/10 tw-font-medium tw-text-sm tw-px-4 tw-py-2.5 tw-no-underline tw-transition-colors"
          >
            <i class="fas fa-arrow-left tw-text-xs"></i>
            Retour à l'agenda
          </a>
          <button
            v-if="canCreate"
            class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-white tw-text-[#1D4ED8] hover:tw-bg-[#BFDBFE] tw-font-semibold tw-text-sm tw-px-4 tw-py-2.5 tw-border-0 tw-shadow-sm tw-transition-colors tw-cursor-pointer"
            @click="openCreateModal"
          >
            <i class="fas fa-plus tw-text-xs"></i>
            Nouveau Rendez-vous
          </button>
        </div>

      </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════
         TOOLBAR — Nav controls + period title + view switcher
    ════════════════════════════════════════════════════════ -->
    <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-px-5 tw-py-3 tw-flex tw-items-center tw-justify-between tw-flex-wrap tw-gap-3">

      <!-- Prev / Today / Next -->
      <div class="tw-flex tw-items-center tw-gap-1.5">
        <button
          class="tw-w-9 tw-h-9 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white hover:tw-bg-slate-50 tw-text-slate-600 tw-flex tw-items-center tw-justify-center tw-transition-colors tw-cursor-pointer"
          @click="navigatePrev"
          title="Mois précédent"
        >
          <i class="fas fa-chevron-left tw-text-xs"></i>
        </button>
        <button
          class="tw-w-9 tw-h-9 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white hover:tw-bg-slate-50 tw-text-slate-600 tw-flex tw-items-center tw-justify-center tw-transition-colors tw-cursor-pointer"
          @click="navigateNext"
          title="Mois suivant"
        >
          <i class="fas fa-chevron-right tw-text-xs"></i>
        </button>
      
      </div>

      <!-- Current period -->
      <h2 class="tw-text-base tw-font-bold tw-text-slate-700 tw-capitalize tw-mb-0">
        {{ calendarTitle }}
      </h2>

      <!-- View switcher pill -->
      <div class="tw-flex tw-items-center tw-gap-1 tw-bg-slate-100 tw-rounded-xl tw-p-1">
        <button
          class="tw-h-7 tw-px-4 tw-rounded-lg tw-text-xs tw-font-semibold tw-border-0 tw-transition-colors tw-cursor-pointer"
          :class="currentView === 'dayGridMonth'
            ? 'tw-bg-white tw-text-[#1D4ED8] tw-shadow-sm'
            : 'tw-bg-transparent tw-text-slate-500 hover:tw-text-slate-700'"
          @click="changeView('dayGridMonth')"
        >
          <i class="fas fa-calendar tw-mr-1.5 tw-text-[10px]"></i>Mois
        </button>
        <button
          class="tw-h-7 tw-px-4 tw-rounded-lg tw-text-xs tw-font-semibold tw-border-0 tw-transition-colors tw-cursor-pointer"
          :class="currentView === 'listWeek'
            ? 'tw-bg-white tw-text-[#1D4ED8] tw-shadow-sm'
            : 'tw-bg-transparent tw-text-slate-500 hover:tw-text-slate-700'"
          @click="changeView('listWeek')"
        >
          <i class="fas fa-list tw-mr-1.5 tw-text-[10px]"></i>Liste
        </button>
      </div>

    </div>

    <!-- ═══════════════════════════════════════════════════════
         STATUS LEGEND
    ════════════════════════════════════════════════════════ -->
    <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-px-5 tw-py-3.5">
      <p class="tw-text-[10px] tw-font-bold tw-text-slate-400 tw-uppercase tw-tracking-widest tw-mb-2.5">Légende des statuts</p>
      <div class="tw-flex tw-flex-wrap tw-gap-4">
        <div
          v-for="status in statuses"
          :key="status.key"
          class="tw-flex tw-items-center tw-gap-2"
        >
          <span
            class="tw-w-3 tw-h-3 tw-rounded-sm tw-shrink-0"
            :style="{ backgroundColor: status.color }"
          ></span>
          <span class="tw-text-xs tw-font-medium tw-text-slate-600">{{ status.label }}</span>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════
         CONTENT ROW — Médecins sidebar + Calendar
    ════════════════════════════════════════════════════════ -->
    <div class="tw-flex tw-gap-5 tw-items-stretch">

      <!-- ── Médecins sidebar ──────────────────────── -->
      <div
        v-if="showSidebar"
        class="tw-w-56 tw-shrink-0 tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-flex tw-flex-col tw-overflow-hidden medecin-sidebar-card"
      >
        <!-- sidebar header -->
        <div class="tw-px-4 tw-py-3 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2 tw-shrink-0">
          <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center">
            <i class="fas fa-user-md tw-text-[#1D4ED8] tw-text-xs"></i>
          </div>
          <h3 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Médecins</h3>
        </div>
        <!-- medecin list -->
        <div class="tw-flex-1 tw-overflow-y-auto tw-p-2.5 tw-space-y-1 medecin-scroll">
          <div
            v-for="medecin in medecins"
            :key="medecin.id"
            class="tw-flex tw-items-center tw-gap-2.5 tw-px-3 tw-py-2.5 tw-rounded-xl tw-cursor-pointer tw-transition-all tw-text-sm tw-border tw-border-transparent"
            :class="selectedMedecinId === medecin.id
              ? 'tw-bg-[#1D4ED8] tw-text-white tw-border-[#1D4ED8] tw-shadow-sm'
              : 'tw-text-slate-600 hover:tw-bg-slate-50 hover:tw-border-slate-200'"
            @click="selectMedecin(medecin.id)"
          >
            <!-- avatar initial -->
            <div
              class="tw-w-7 tw-h-7 tw-rounded-lg tw-flex tw-items-center tw-justify-center tw-shrink-0 tw-text-xs tw-font-bold"
              :class="selectedMedecinId === medecin.id
                ? 'tw-bg-white/25 tw-text-white'
                : 'tw-bg-[#BFDBFE] tw-text-[#1D4ED8]'"
            >
              {{ medecin.name.charAt(0).toUpperCase() }}
            </div>
            <span class="tw-font-medium tw-leading-tight tw-text-xs">Dr. {{ medecin.name }} {{ medecin.prenom }}</span>
          </div>
          <!-- empty state -->
          <div v-if="!medecins.length" class="tw-text-center tw-py-6 tw-text-xs tw-text-slate-400">
            <i class="fas fa-user-slash tw-block tw-text-2xl tw-mb-2 tw-text-slate-300"></i>
            Aucun médecin
          </div>
        </div>
      </div>

      <!-- ── Calendar area ─────────────────────────── -->
      <div class="tw-flex-1 tw-min-w-0 tw-flex tw-flex-col tw-gap-4">

        <!-- Loading skeleton -->
        <div
          v-if="loading"
          class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-p-6"
        >
          <div class="tw-h-8 tw-rounded-xl tw-mb-5 skeleton-shimmer"></div>
          <div class="tw-grid tw-gap-3 tw-grid-cols-7">
            <div v-for="n in 35" :key="n" class="tw-h-24 tw-rounded-xl skeleton-shimmer"></div>
          </div>
        </div>

        <!-- Error -->
        <div
          v-if="error"
          class="tw-flex tw-items-start tw-gap-3 tw-rounded-xl tw-bg-red-50 tw-border tw-border-red-200 tw-px-4 tw-py-3.5 tw-text-sm tw-text-red-600"
        >
          <i class="fas fa-exclamation-triangle tw-mt-0.5 tw-shrink-0 tw-text-red-400"></i>
          <span>{{ error }}</span>
        </div>

        <!-- FullCalendar -->
        <div
          v-show="!loading && !error"
          class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-p-5 calendar-container"
        >
          <FullCalendar ref="fullCalendar" :options="calendarOptions" />
        </div>

      </div>
    </div><!-- /.content-row -->


    <!-- ═══════════════════════════════════════════════════════
         MODAL — Create / Edit Rendez-vous
         Bootstrap modal shell kept; inner design replaced
    ════════════════════════════════════════════════════════ -->
    <div
      class="modal fade"
      id="eventModal"
      tabindex="-1"
      ref="eventModal"
      aria-hidden="true"
      data-bs-backdrop="static"
    >
      <div class="modal-dialog modal-dialog-centered modal-lg">
           <!-- modal-content class REQUIRED for Bootstrap focus-trap/dismiss -->
        <div class="modal-content tw-border-0 tw-rounded-2xl tw-overflow-hidden tw-shadow-2xl">

          <!-- Modal header gradient -->
          <div class="tw-px-6 tw-py-4 tw-bg-gradient-to-r tw-from-[#1D4ED8] tw-to-[#2563eb] tw-flex tw-items-center tw-justify-between">
            <h2 class="tw-text-base tw-font-semibold tw-text-white tw-mb-0 tw-flex tw-items-center tw-gap-2">
              <i class="fas" :class="editingEvent ? 'fa-pen' : 'fa-plus-circle'"></i>
              {{ editingEvent ? 'Modifier le statut du' : 'Nouveau' }} Rendez-vous
            </h2>
            <button
              type="button"
              class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-white/20 hover:tw-bg-white/35 tw-border-0 tw-text-white tw-flex tw-items-center tw-justify-center tw-transition-colors tw-cursor-pointer"
              data-bs-dismiss="modal"
              aria-label="Fermer"
            >
              <i class="fas fa-times tw-text-sm"></i>
            </button>
          </div>

          <!-- Edit-mode info banner -->
          <div
            v-if="editingEvent"
            class="tw-flex tw-items-start tw-gap-3 tw-bg-[#BFDBFE]/25 tw-border-b tw-border-[#BFDBFE]/60 tw-px-6 tw-py-3 tw-text-sm tw-text-[#1D4ED8]"
          >
            <i class="fas fa-lock tw-mt-0.5 tw-shrink-0 tw-text-xs"></i>
            <span>Vous pouvez uniquement modifier le statut du rendez-vous. Les autres informations sont verrouillées.</span>
          </div>

          <!-- Form body -->
          <form @submit.prevent="saveEvent">
            <div class="tw-p-6 tw-space-y-5 tw-max-h-[72vh] tw-overflow-y-auto">

              <!-- Patient search + select -->
              <div>
                <label class="tw-block tw-text-xs tw-font-bold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-mb-2">
                  Patient <span v-if="!editingEvent" class="tw-text-red-400">*</span>
                </label>
                <input
                  v-model="patientSearchQuery"
                  type="text"
                  class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3.5 tw-py-2.5 tw-text-sm tw-text-slate-700 tw-mb-2 tw-transition-colors focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] disabled:tw-opacity-50 disabled:tw-cursor-not-allowed"
                  placeholder="Rechercher un patient…"
                  @focus="loadAllPatients"
                  :disabled="!!editingEvent"
                >
                <select
                  v-model="eventForm.patient_id"
                  class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3.5 tw-py-2.5 tw-text-sm tw-text-slate-700 tw-transition-colors focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] disabled:tw-opacity-50 disabled:tw-cursor-not-allowed patient-select"
                  :required="!editingEvent"
                  :disabled="!!editingEvent"
                  @scroll="handlePatientScroll"
                  @focus="loadAllPatients"
                >
                  <option value="">Sélectionner un patient</option>
                  <option v-for="patient in filteredPatients" :key="patient.id" :value="patient.id">
                    {{ patient.name }} {{ patient.prenom }}
                  </option>
                  <option v-if="isLoadingPatients" disabled>Chargement…</option>
                </select>
                <p class="tw-text-[11px] tw-text-slate-400 tw-mt-1.5 tw-mb-0">
                  <i class="fas fa-search tw-mr-1"></i>Tapez pour filtrer ou faites défiler pour charger plus
                </p>
              </div>

              <!-- Médecin select (when not fixed) -->
              <div v-if="!medecinId">
                <label class="tw-block tw-text-xs tw-font-bold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-mb-2">
                  Médecin <span class="tw-text-red-400">*</span>
                </label>
                <select
                  v-model="eventForm.medecin_id"
                  class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3.5 tw-py-2.5 tw-text-sm tw-text-slate-700 tw-transition-colors focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] disabled:tw-opacity-50 disabled:tw-cursor-not-allowed"
                  :required="!editingEvent"
                  :disabled="!!editingEvent"
                >
                  <option value="">Sélectionner un médecin</option>
                  <option v-for="medecin in medecins" :key="medecin.id" :value="medecin.id">
                    Dr. {{ medecin.name }} {{ medecin.prenom }}
                  </option>
                </select>
              </div>

              <!-- Objet — pill radio group -->
              <div>
                <label class="tw-block tw-text-xs tw-font-bold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-mb-2">
                  Objet <span class="tw-text-red-400">*</span>
                </label>
                <div class="tw-flex tw-flex-wrap tw-gap-2">
                  <label
                    v-for="obj in ['Consultation', 'Examen', 'Acte', 'Autres']"
                    :key="obj"
                    class="tw-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-rounded-xl tw-border tw-text-sm tw-font-medium tw-transition-all tw-select-none"
                    :class="[
                      editingEvent ? 'tw-opacity-50 tw-cursor-not-allowed' : 'tw-cursor-pointer',
                      eventForm.objet === obj
                        ? 'tw-bg-[#1D4ED8] tw-border-[#1D4ED8] tw-text-white tw-shadow-sm'
                        : 'tw-bg-white tw-border-slate-200 tw-text-slate-600 hover:tw-border-[#1D4ED8] hover:tw-text-[#1D4ED8]'
                    ]"
                  >
                    <input
                      v-model="eventForm.objet"
                      type="radio"
                      :value="obj"
                      :id="`objet${obj}`"
                      :disabled="!!editingEvent"
                      :required="!editingEvent"
                      class="tw-sr-only"
                    >
                    {{ obj }}
                  </label>
                </div>
              </div>

              <!-- Title (auto-generated, read-only) -->
              <div>
                <label class="tw-block tw-text-xs tw-font-bold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-mb-2">Titre</label>
                <input
                  v-model="eventForm.title"
                  type="text"
                  class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-100 tw-px-3.5 tw-py-2.5 tw-text-sm tw-text-slate-400 tw-cursor-not-allowed focus:tw-outline-none"
                  placeholder="Généré automatiquement depuis le patient"
                  readonly
                >
              </div>

              <!-- Date -->
              <div>
                <label class="tw-block tw-text-xs tw-font-bold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-mb-2">
                  Date de rendez-vous <span v-if="!editingEvent" class="tw-text-red-400">*</span>
                </label>
                <input
                  v-model="eventForm.start_date"
                  type="date"
                  class="tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3.5 tw-py-2.5 tw-text-sm tw-text-slate-700 tw-transition-colors focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] disabled:tw-opacity-50 disabled:tw-cursor-not-allowed"
                  :required="!editingEvent"
                  :disabled="!!editingEvent"
                  :min="getTodayDate()"
                >
              </div>

              <!-- Commentaire -->
              <div>
                <label class="tw-block tw-text-xs tw-font-bold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-mb-2">Commentaire</label>
                <textarea
                  v-model="eventForm.description"
                  rows="3"
                  class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3.5 tw-py-2.5 tw-text-sm tw-text-slate-700 tw-resize-y tw-transition-colors focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] read-only:tw-bg-slate-100 read-only:tw-cursor-not-allowed"
                  placeholder="Notes ou informations complémentaires…"
                  :readonly="!!editingEvent"
                ></textarea>
              </div>

              <!-- Statut (edit mode only) — coloured pill radios -->
              <div v-if="editingEvent">
                <label class="tw-block tw-text-xs tw-font-bold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-mb-2">Statut</label>
                <div class="tw-flex tw-flex-wrap tw-gap-2">
                  <template v-for="status in statuses" :key="status.key">
                    <label
                      v-if="status.key !== 'absence excusé' && status.key !== 'absence non excusé' || userRole !== 2"
                      class="tw-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-rounded-xl tw-border tw-text-sm tw-font-medium tw-transition-all tw-cursor-pointer tw-select-none"
                      :style="eventForm.statut === status.key
                        ? { backgroundColor: status.color, borderColor: status.color, color: '#fff' }
                        : { backgroundColor: '#fff', borderColor: '#e2e8f0', color: '#475569' }"
                    >
                      <input
                        type="radio"
                        class="tw-sr-only"
                        name="statut"
                        :id="`statut-${status.key}`"
                        v-model="eventForm.statut"
                        :value="status.key"
                        autocomplete="off"
                      >
                      <span
                        class="tw-w-2 tw-h-2 tw-rounded-full tw-shrink-0"
                        :style="{ backgroundColor: eventForm.statut === status.key ? 'rgba(255,255,255,0.6)' : status.color }"
                      ></span>
                      {{ status.label }}
                    </label>
                  </template>
                </div>
                <p v-if="userRole === 2" class="tw-text-[11px] tw-text-slate-400 tw-mt-2 tw-mb-0">
                  <i class="fas fa-info-circle tw-mr-1"></i>
                  En tant que médecin, vous pouvez changer le statut à "à venir", "vu" ou "reporté" uniquement.
                </p>
              </div>

              <!-- Report date (only when reporté) -->
              <div
                v-if="editingEvent && eventForm.statut === 'reporté'"
                class="tw-rounded-xl tw-bg-[#BFDBFE]/20 tw-border tw-border-[#BFDBFE] tw-p-4"
              >
                <h4 class="tw-text-sm tw-font-semibold tw-text-[#1D4ED8] tw-mb-3 tw-flex tw-items-center tw-gap-2">
                  <i class="fas fa-calendar-plus"></i>
                  Nouvelle date du rendez-vous
                </h4>
                <div>
                  <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                    Nouvelle date <span class="tw-text-red-400">*</span>
                  </label>
                  <input
                    v-model="eventForm.new_report_date"
                    type="date"
                    class="tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-px-3.5 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]"
                    :min="getTodayDate()"
                    required
                  >
                </div>
                <p class="tw-text-[11px] tw-text-slate-400 tw-mt-2.5 tw-mb-0">
                  <i class="fas fa-info-circle tw-mr-1"></i>Le rendez-vous sera déplacé à cette nouvelle date.
                </p>
              </div>

            </div><!-- /.form body -->

            <!-- Modal footer -->
            <div
              v-if="userRole !== 9"
              class="tw-px-6 tw-py-4 tw-border-t tw-border-slate-100 tw-flex tw-items-center tw-justify-between tw-gap-3 tw-flex-wrap"
            >
              <!-- Left: patient file link -->
              <button
                v-if="editingEvent && eventForm.patient_id"
                type="button"
                class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-border tw-border-teal-200 tw-bg-teal-50 hover:tw-bg-teal-100 tw-text-teal-700 tw-font-medium tw-text-sm tw-px-4 tw-py-2.5 tw-transition-colors tw-cursor-pointer"
                @click="openPatientFile"
              >
                <i class="fas fa-folder-open tw-text-xs"></i> Ouvrir dossier patient
              </button>
              <div v-else></div>

              <!-- Right: cancel / delete / save -->
              <div class="tw-flex tw-items-center tw-gap-2">
                <button
                  type="button"
                  class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white hover:tw-bg-slate-50 tw-text-slate-600 tw-font-medium tw-text-sm tw-px-4 tw-py-2.5 tw-transition-colors tw-cursor-pointer disabled:tw-opacity-50"
                  data-bs-dismiss="modal"
                  :disabled="saving"
                >
                  Annuler
                </button>
                <button
                  v-if="editingEvent && canDelete"
                  type="button"
                  class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-red-500 hover:tw-bg-red-600 tw-text-white tw-font-semibold tw-text-sm tw-px-4 tw-py-2.5 tw-border-0 tw-transition-colors tw-cursor-pointer disabled:tw-opacity-50"
                  @click="deleteEvent"
                  :disabled="saving"
                >
                  <i class="fas fa-trash tw-text-xs"></i> Supprimer
                </button>
                <button
                  type="submit"
                  class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-font-semibold tw-text-sm tw-px-5 tw-py-2.5 tw-border-0 tw-transition-colors tw-cursor-pointer disabled:tw-opacity-50"
                  :disabled="saving"
                >
                  <svg v-if="saving" class="tw-animate-spin tw-w-4 tw-h-4 tw-shrink-0" viewBox="0 0 24 24" fill="none">
                    <circle class="tw-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="tw-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                  </svg>
                  <i v-else class="fas fa-save tw-text-xs"></i>
                  {{ saving ? 'Enregistrement…' : 'Enregistrer' }}
                </button>
              </div>
            </div>

          </form>
        </div><!-- /.modal card -->
      </div>
    </div><!-- /.modal -->

  </div><!-- /root -->
</template>

<script setup>
import { ref, computed, onMounted, reactive, watch, nextTick, onBeforeUnmount } from 'vue'
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

// Status data to avoid repetition
const statuses = [
  { key: 'a venir', label: 'À venir', color: '#4682B4' },
  { key: 'vu', label: 'Vu', color: '#008B8B' },
  { key: 'absence excusé', label: 'Absence excusée', color: '#DDA0DD' },
  { key: 'absence non excusé', label: 'Absence non excusée', color: '#6A5ACD' },
  { key: 'reporté', label: 'Reporté', color: '#FF6347' }
]

const statusClasses = {
  'a venir': 'btn-outline-steelblue',
  'vu': 'btn-outline-darkcyan',
  'absence excusé': 'btn-outline-plum',
  'absence non excusé': 'btn-outline-slateblue',
  'reporté': 'btn-outline-tomato'
}

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
    aspectRatio: 1.1,
    resourceAreaWidth: '15%',
    events: events.value,
    resources: resources.value,
    editable: false, // Disable drag and drop since we're date-only
    selectable: props.editable && (props.canCreate || props.userRole === 2),
    selectAllow: (selectInfo) => {
      const selectedDate = new Date(selectInfo.start)
      selectedDate.setHours(0, 0, 0, 0)
      const today = new Date()
      today.setHours(0, 0, 0, 0)
      return selectedDate >= today
    },
    select: handleDateSelect,
    eventClick: handleEventClick,
    nowIndicator: true,
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
  description: '',
  statut: 'a venir',
  new_report_date: ''
})

// ==================== AXIOS SETUP ====================
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')

// ==================== WATCHERS ====================
watch([() => eventForm.patient_id, () => eventForm.objet], ([newPatientId, newObjet]) => {
  const patient = allPatients.value.find(p => p.id == newPatientId)
  if (patient) {
    eventForm.title = `${patient.name} ${patient.prenom}${newObjet ? ' - ' + newObjet : ''}`
  } else {
    eventForm.title = ''
  }
})

watch(() => eventForm.statut, (newStatut) => {
  if (newStatut !== 'reporté') {
    eventForm.new_report_date = ''
  }
})

watch(patientSearchQuery, (newQuery) => {
  patientPage.value = 1
  searchPatients(newQuery)
})

// ==================== UTILITY FUNCTIONS ====================
const getTodayDate = () => {
  const today = new Date()
  const year = today.getFullYear()
  const month = String(today.getMonth() + 1).padStart(2, '0')
  const day = String(today.getDate()).padStart(2, '0')
  return `${year}-${month}-${day}`
}

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
  } else if (viewType === 'listWeek') {
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
          title: event.patient ? `${event.patient.name} ${event.patient.prenom}${event.objet ? ' - ' + event.objet : ''}` : (event.title || ''),
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

  // Get selected date
  const selectedDate = new Date(selectInfo.start)
  selectedDate.setHours(0, 0, 0, 0)
  
  // Get today's date
  const today = new Date()
  today.setHours(0, 0, 0, 0)

  // Check if selected date is in the past (before today)
  if (selectedDate < today) {
    flashMessage.value?.warning('Vous ne pouvez pas créer un rendez-vous pour une date passée.')
    return
  }

  // Reset form
  editingEvent.value = null
  eventForm.patient_id = ''
  eventForm.medecin_id = props.medecinId || ''
  eventForm.title = ''
  eventForm.objet = 'Consultation'
  eventForm.description = ''
  eventForm.statut = 'a venir'
  eventForm.new_report_date = ''

  // Set selected date (format: YYYY-MM-DD)
  eventForm.start_date = selectInfo.startStr.split('T')[0]

  // Set medecin if clicking on resource
  if (selectInfo.resource) {
    eventForm.medecin_id = selectInfo.resource.id
  }

  openModal()
  
  // Unselect in calendar
  const calendarApi = fullCalendar.value?.getApi()
  if (calendarApi) {
    calendarApi.unselect()
  }
}

const handleEventClick = (clickInfo) => {
  // Check if user is comptable (role 9) - they can only view, not edit
  if (props.userRole === 9) {
    flashMessage.value?.warning('Vous n\'avez pas de permission pour cette action.')
    return false // Prevent default FullCalendar event click behavior
  }

  const event = clickInfo.event
  editingEvent.value = event

  // Set form values (date only, no time)
  eventForm.patient_id = event.extendedProps.patient_id
  eventForm.medecin_id = event.extendedProps.medecin_id
  eventForm.title = event.title
  eventForm.objet = event.extendedProps.objet || 'Consultation'
  eventForm.start_date = event.startStr.split('T')[0]
  eventForm.description = event.extendedProps.description || ''
  eventForm.statut = event.extendedProps.statut || 'a venir'

  openModal()
  return true
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
  // eventForm.start_time = '09:00'
  eventForm.end_date = ''
  // eventForm.end_time = '10:00'
  eventForm.description = ''
  eventForm.statut = 'a venir'
  eventForm.original_start_date = ''
  // eventForm.original_start_time = ''
  eventForm.new_report_date = ''
  // eventForm.new_report_time = ''
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
    if (!eventForm.new_report_date) {
      flashMessage.value?.warning('Veuillez choisir une nouvelle date pour le report.')
      return
    }
    
    const newDate = new Date(eventForm.new_report_date)
    newDate.setHours(0, 0, 0, 0)
    const today = new Date()
    today.setHours(0, 0, 0, 0)
    
    if (newDate < today) {
      flashMessage.value?.warning('La nouvelle date de report ne peut pas être dans le passé.')
      return
    }
  }
  
  // Check if creating event for past date
  if (!editingEvent.value) {
    const startDate = new Date(eventForm.start_date)
    startDate.setHours(0, 0, 0, 0)
    const today = new Date()
    today.setHours(0, 0, 0, 0)
    
    if (startDate < today) {
      flashMessage.value?.warning('Vous ne pouvez pas créer un rendez-vous pour une date passée.')
      return
    }
  }
  
  const calendarApi = fullCalendar.value?.getApi()
  const previousViewType = calendarApi?.view.type
  const previousDate = calendarApi ? calendarApi.getDate() : null
  
  saving.value = true
  
  try {
    const patient = allPatients.value.find(p => p.id == eventForm.patient_id)
    const title = patient ? `${patient.name} ${patient.prenom}${eventForm.objet ? ' - ' + eventForm.objet : ''}` : eventForm.title
    
    // For normal events (not being reported)
    let startDateTime = `${eventForm.start_date}T${eventForm.start_time}:00`
    // let endDateTime = `${eventForm.end_date || eventForm.start_date}T${eventForm.end_time}:00`

    const eventData = {
      patient_id: eventForm.patient_id,
      medecin_id: eventForm.medecin_id || props.medecinId,
      title: title,
      objet: eventForm.objet,
      start: eventForm.start_date, // Just date, no time
      description: eventForm.description,
      statut: eventForm.statut
    }

    // Add new date/time if status is "reporté"
    if (eventForm.statut === 'reporté' && eventForm.new_report_date) {
      eventData.new_report_date = eventForm.new_report_date
    }
    
    if (editingEvent.value) {
      const response = await axios.put(`/admin/events/${editingEvent.value.id}`, eventData)
      
      // Update the original event
      editingEvent.value.setProp('title', response.data.event.title)
      editingEvent.value.setStart(response.data.event.start)
      editingEvent.value.setEnd(response.data.event.end)
      editingEvent.value.setProp('backgroundColor', response.data.event.color || getColorByStatut(response.data.event.statut))
      editingEvent.value.setProp('borderColor', response.data.event.color || getColorByStatut(response.data.event.statut))
      editingEvent.value.setExtendedProp('statut', response.data.event.statut)
      
      // Reload all events to show the new reported event
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
    
  }catch (err) {
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

// Sync sidebar height to the FullCalendar daygrid body
const adjustHeights = async () => {
  await nextTick()
  try {
    // The actual month grid rendered by FullCalendar
    const fcGrid = document.querySelector('.fc-daygrid-body') ||
                   document.querySelector('.fc-scrollgrid') ||
                   document.querySelector('.calendar-container .fc')
    const sidebarCard = document.querySelector('.medecin-sidebar-card')

    if (fcGrid && sidebarCard) {
      const gridHeight = fcGrid.getBoundingClientRect().height
      if (gridHeight > 100) {
        // Add the card's vertical padding (20px top + 20px bottom = 40px, matching tw-p-5)
        const containerPadding = 240
        sidebarCard.style.height = `${gridHeight + containerPadding}px`
        sidebarCard.style.minHeight = `${gridHeight + containerPadding}px`
      }
    }
  } catch (e) {
    // silent
  }
}

// call adjust after load and on window resize
const boundAdjust = debounce(() => adjustHeights(), 120)
window.addEventListener('resize', boundAdjust)
watch(loading, (val) => {
  if (!val) {
    // after loading finishes, ensure heights match
    setTimeout(() => adjustHeights(), 50)
  }
})

onBeforeUnmount(() => {
  window.removeEventListener('resize', boundAdjust)
})

// ==================== EXPOSE ====================
defineExpose({
  loadEvents,
  getApi: () => fullCalendar.value?.getApi()
})

</script>


<style scoped>
/* ── Skeleton shimmer animation ─────────────────────── */
@keyframes shimmer {
  0%   { background-position: 200% 0; }
  100% { background-position: -200% 0; }
}
.skeleton-shimmer {
  background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
  background-size: 200% 100%;
  animation: shimmer 1.6s infinite;
}

/* Medecins sidebar: JS will sync height to calendar grid */
.medecin-sidebar-card {
  min-height: 800px; /* safe floor (+100px); JS overrides */
}

/* ── Smooth scrollbar for the medecin list ──────────── */
.medecin-scroll {
  scrollbar-width: thin;
  scrollbar-color: #cbd5e1 transparent;
}
.medecin-scroll::-webkit-scrollbar       { width: 4px; }
.medecin-scroll::-webkit-scrollbar-track { background: transparent; }
.medecin-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }

/* ── Patient select scrollbar ───────────────────────── */
.patient-select {
  max-height: 220px;
  overflow-y: auto;
  scrollbar-width: thin;
  scrollbar-color: #cbd5e1 transparent;
}
.patient-select::-webkit-scrollbar       { width: 6px; }
.patient-select::-webkit-scrollbar-track { background: transparent; }
.patient-select::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }

/* ── Calendar container ─────────────────────────────── */
.calendar-container {
  min-height: 750px;
}

/* ── FC license notice: repositioned below the grid ────── */
/* FC injects .fc-license-message with position:absolute overlaying the grid.
   We reset it to static so it flows naturally at the bottom of the .fc element. */
:deep(.fc-license-message) {
  position: static !important;
  display: block;
  margin-top: 10px;
  padding: 4px 0 0 0;
  font-size: 0.7rem !important;
  color: #94a3b8 !important;
  background: transparent !important;
  border: none !important;
  text-align: left;
  z-index: auto !important;
}

/* ── FullCalendar theme overrides ──────────────────── */
/* Keep only what Tailwind cannot control (FC internals) */
:deep(.fc) {
  font-family: inherit;
}
:deep(.fc .fc-button) {
  display: none; /* we use our own navigation buttons */
}
:deep(.fc-theme-bootstrap5 .fc-list-event:hover td) {
  background-color: #eff6ff;
}
:deep(.fc-event) {
  border-radius: 6px !important;
  font-size: 0.75rem !important;
  font-weight: 500 !important;
  padding: 1px 4px !important;
  border: none !important;
}
:deep(.fc-daygrid-day-number),
:deep(.fc-col-header-cell-cushion) {
  color: #475569;
  font-size: 0.75rem;
  font-weight: 600;
  text-decoration: none !important;
}
:deep(.fc-day-today) {
  background-color: #eff6ff !important;
}
:deep(.fc-day-today .fc-daygrid-day-number) {
  color: #1D4ED8;
  font-weight: 700;
}
:deep(.fc-scrollgrid) {
  border-color: #e2e8f0 !important;
}
:deep(.fc-scrollgrid td),
:deep(.fc-scrollgrid th) {
  border-color: #e2e8f0 !important;
}
:deep(.fc-list-day-cushion) {
  background-color: #f8fafc !important;
  font-size: 0.75rem;
  font-weight: 700;
  color: #64748b;
  text-transform: uppercase;
  letter-spacing: 0.06em;
}
:deep(.fc-list-event-title a) {
  text-decoration: none;
  color: #1e293b;
  font-size: 0.8rem;
}
:deep(.fc-list-event-time) {
  color: #94a3b8;
  font-size: 0.75rem;
}


</style>