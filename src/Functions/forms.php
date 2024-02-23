<?php

function _input(string $name, string $label, string $type, string|int|null $value): string
{
    if ($type == "hidden") {
        return <<<HTML
            <input autocomplete="off" type="$type" class="form-control" id="$name"  name="$name" value="$value" aria-describedby="$name" required>
        HTML;

    }
    return <<<HTML
<div class="mb-3">
   <label for="$name" class="form-label" style="font-weight: normal">$label</label> 
    <input autocomplete="off" type="$type" class="form-control" id="$name"  name="$name" value="$value" aria-describedby="$name" required>
    <div class="invalid-feedback">
        Veuillez fournir un <span style="text-transform:lowercase;">$label</span> valide.
    </div>
</div>

HTML;

}

function _select($label, $name, array $data, $key_label, $value_label, $value = null): string
{

    $option = '';
    foreach ($data as $item) {
        $_val = $item[$key_label];
        $_libelle = $item[$value_label];
        $selected = $_val == $value ? 'selected' : '';
        $option .= <<<HTML
            <option value="$_val" $selected>$_libelle</option>
        HTML;

    }
    return <<<HTML
        <div class="mb-3">
           <label for="$name" class="form-label" style="font-weight: normal">$label</label> 
            <select id="$name" name="$name" class="form-select" required>
                <option selected disabled value=""> $label ...</option>
                $option
            </select>
             <div class="invalid-feedback">
                Veuillez fournir un <span style="text-transform:lowercase;">$label</span> valide.
            </div>
        </div>

HTML;

}