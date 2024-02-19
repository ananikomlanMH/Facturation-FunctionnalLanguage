<?php

function _input(string $name, string $label, string $type, string|int|null $value): string
{
    if ($type == "hidden"){
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