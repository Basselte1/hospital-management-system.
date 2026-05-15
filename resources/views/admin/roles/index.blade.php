@extends('layouts.admin')
@section('title', 'CMCU | Liste des rôles')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">

    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">

        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Page heading --}}
            <div class="tw-mb-6 tw-flex tw-items-center tw-justify-between tw-flex-wrap tw-gap-3">
                <div>
                    <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Rôles</h1>
                    <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Gérez les rôles et permissions du système</p>
                </div>
                <a href="{{ route('roles.create') }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-[#1D4ED8] tw-text-white tw-text-sm tw-font-medium tw-rounded-lg hover:tw-bg-[#1a46c5] tw-transition-colors tw-duration-150 tw-no-underline tw-shadow-sm">
                    <i class="fas fa-plus tw-text-xs"></i>
                    Ajouter un rôle
                </a>
            </div>

            {{-- Table card --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">

                {{-- Card header --}}
                <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                    <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-user-shield tw-text-[#1D4ED8] tw-text-xs"></i>
                    </div>
                    <h2 class="tw-text-base tw-font-semibold tw-text-slate-700">Liste des rôles</h2>
                    <span class="tw-ml-auto tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-[#BFDBFE] tw-text-[#1D4ED8]">
                        {{ count($roles) }} rôle{{ count($roles) > 1 ? 's' : '' }}
                    </span>
                </div>

                {{-- Table --}}
                <div class="tw-overflow-x-auto">
                    <table id="myTable" class="tw-w-full tw-text-sm tw-text-left">
                        <thead class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                            <tr>
                                <th class="tw-px-6 tw-py-3 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-w-16">ID</th>
                                <th class="tw-px-6 tw-py-3 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Rôle</th>
                                <th class="tw-px-6 tw-py-3 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-text-center tw-w-28">Modifier</th>
                                <th class="tw-px-6 tw-py-3 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-text-center tw-w-28">Supprimer</th>
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-100">
                            @foreach ($roles as $role)
                            <tr class="hover:tw-bg-slate-50 tw-transition-colors tw-duration-100">
                                <td class="tw-px-6 tw-py-4 tw-text-slate-400 tw-font-mono tw-text-xs">#{{ $role->id }}</td>
                                <td class="tw-px-6 tw-py-4">
                                    <div class="tw-flex tw-items-center tw-gap-2.5">
                                        <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                            <i class="fas fa-user-shield tw-text-[#1D4ED8] tw-text-[10px]"></i>
                                        </div>
                                        <span class="tw-font-medium tw-text-slate-800">{{ $role->name }}</span>
                                    </div>
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-text-center">
                                    <a href="{{ route('roles.edit', $role->id) }}"
                                       title="Modifier ce rôle"
                                       class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE] tw-text-[#1D4ED8] hover:tw-bg-[#1D4ED8] hover:tw-text-white tw-transition-colors tw-duration-150 tw-no-underline">
                                        <i class="far fa-edit tw-text-xs"></i>
                                    </a>
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-text-center">
                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="tw-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                title="Supprimer ce rôle"
                                                onclick="return confirm('Confirmer la suppression du rôle « {{ addslashes($role->name) }} » ?')"
                                                class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-red-50 tw-text-red-500 hover:tw-bg-red-500 hover:tw-text-white tw-transition-colors tw-duration-150 tw-border-0 tw-cursor-pointer">
                                            <i class="fas fa-trash-alt tw-text-xs"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach

                            @if(count($roles) === 0)
                            <tr>
                                <td colspan="4" class="tw-px-6 tw-py-12 tw-text-center">
                                    <div class="tw-flex tw-flex-col tw-items-center tw-gap-3">
                                        <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-slate-100 tw-flex tw-items-center tw-justify-center">
                                            <i class="fas fa-user-shield tw-text-slate-400 tw-text-xl"></i>
                                        </div>
                                        <p class="tw-text-slate-500 tw-text-sm">Aucun rôle enregistré</p>
                                        <a href="{{ route('roles.create') }}" class="tw-text-[#1D4ED8] tw-text-sm hover:tw-underline tw-no-underline">
                                            Créer le premier rôle
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                @if($roles->hasPages())
                <div class="tw-px-6 tw-py-4 tw-border-t tw-border-slate-100">
                    {{ $roles->links() }}
                </div>
                @endif
            </div>

        </main>
    </div>
</div>
@stop