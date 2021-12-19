<div class="row numeric-pad">
    <div class="col">
        <div class="pad" onclick="writeDown('1')">1</div>
        <div class="pad" onclick="writeDown('4')">4</div>
        <div class="pad" onclick="writeDown('7')">7</div>
        <div class="pad text-warning" onclick="removeLast()">
            &nbsp;
            <svg xmlns="http://www.w3.org/2000/svg" style="width: 32px; height: 32px;" fill="currentColor" class="bi bi-backspace" viewBox="0 0 16 16">
                <path d="M5.83 5.146a.5.5 0 0 0 0 .708L7.975 8l-2.147 2.146a.5.5 0 0 0 .707.708l2.147-2.147 2.146 2.147a.5.5 0 0 0 .707-.708L9.39 8l2.146-2.146a.5.5 0 0 0-.707-.708L8.683 7.293 6.536 5.146a.5.5 0 0 0-.707 0z"/>
                <path d="M13.683 1a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-7.08a2 2 0 0 1-1.519-.698L.241 8.65a1 1 0 0 1 0-1.302L5.084 1.7A2 2 0 0 1 6.603 1h7.08zm-7.08 1a1 1 0 0 0-.76.35L1 8l4.844 5.65a1 1 0 0 0 .759.35h7.08a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1h-7.08z"/>
            </svg>
            &nbsp;
        </div>
        <div class="pad text-danger" onclick="deleteValue()">C</div>
    </div>
    <div class="col">
        <div class="pad" onclick="writeDown('2')">2</div>
        <div class="pad" onclick="writeDown('5')">5</div>
        <div class="pad" onclick="writeDown('8')">8</div>
        <div class="pad" onclick="writeDown('0')">0</div>
    </div>
    <div class="col">
        <div class="pad" onclick="writeDown('3')">3</div>
        <div class="pad" onclick="writeDown('6')">6</div>
        <div class="pad" onclick="writeDown('9')">9</div>
        <div class="pad" onclick="writeDown('.')">.</div>
        <div class="pad text-success" onclick="submitForm()">
            <svg xmlns="http://www.w3.org/2000/svg" style="width: 32px; height: 32px;" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
            </svg>
        </div>
    </div>
</div>

<script lang="js" src="{{ asset('js/numeric-pad.js') }}"></script>

