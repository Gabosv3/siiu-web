<script>
    window.permissions = {
        copy: {{ json_encode(auth()->user()->can('export.copy')) }},
        excel: {{ json_encode(auth()->user()->can('export.excel')) }},
        csv: {{ json_encode(auth()->user()->can('export.csv')) }},
        pdf: {{ json_encode(auth()->user()->can('export.pdf')) }},
        print: {{ json_encode(auth()->user()->can('export.print')) }},
    };
</script>