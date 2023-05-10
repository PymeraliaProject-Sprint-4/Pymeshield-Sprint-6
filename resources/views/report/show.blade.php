@extends('layouts.admin')

@section('content')
    <h1 class="p-3 text-center font-medium">{{ __('report.titlereport') }} {{ $id }}</h1>
    <div class="flex justify-end">

        <a href="{{ route('report.pdf', [$id]) }}" class="botonPersonalizado"
            onclick="return disableButton(this)">{{ __('table.pdf') }}</a>

    </div>
    <div class="overflow-x-auto shadow-md sm:rounded-lg m-5">
        <table class="table-auto w-full shadow-lg rounded-lg">
            <thead>
                <tr class="bg-orange-400 text-white">
                    <th class="px-6 py-3 uppercase">
                        {{ __('report.answer') }}
                    </th>
                    <th class="px-6 py-3 uppercase">
                        {{ __('report.recommendation') }}
                    </th>
                    <th class="px-6 py-3 uppercase">
                        {{ __('report.question') }}
                    </th>
                    <th class="px-6 py-3 uppercase">
                        {{ __('report.type.measure') }}
                    </th>
                    <th class="px-6 py-3 uppercase">
                        {{ __('report.risk') }}
                    </th>
                    <th class="px-6 py-3 uppercase">
                        {{ __('report.probability') }}
                    </th>
                    <th class="px-6 py-3 uppercase">
                        {{ __('report.intervention') }}
                    </th>
                    <th class="px-6 py-3 uppercase">
                        {{ __('report.impact') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($report as $row)
                    <tr class="bg-orange-50 hover:bg-orange-100 text-center">
                        <td class="px-6 py-4">
                            {{ $row->answers }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $row->recommendation }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $row->questions }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $row->type_measures }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $row->risks }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $row->probabilities }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $row->interventions }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $row->impacts }}
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
    <component :is="'script'">
        function disableButton(btn) {
            btn.disabled = true;
            btn.innerText = "{{ __('report.processpdf') }}";
            console.log("Funciona");
            return true;
        }
    </component>
@endsection
