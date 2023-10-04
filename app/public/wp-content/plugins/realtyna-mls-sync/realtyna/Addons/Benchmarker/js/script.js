// document.getElementById('hbp-start-test').addEventListener('click', startTest);
// var click = false;

// Start test
function startTest(e) {
    e.preventDefault();
    
    if (!click) {
        // Variables
        const hbpStartRing = document.getElementById('hbp-start-ring');
        const hbpStartBorder = document.getElementById('hbp-start-border');
        const hbpSpeedTest = document.getElementById('hbp-speed-test');
        const hbpMbpsText = document.getElementById('hbp-mbps-text');
        const hbpRunning = document.getElementById('hbp-running');
        let progressBarWidth = 0;

        // Functions
        const progressBar = () => {
            if(progressBarWidth >= 150) {
                clearInterval(progressBarCount);
            } else {
                progressBarWidth++;
                hbpStartBorder.style.clip = `rect(0px, ${progressBarWidth}px, 150px, 0px)`;
                hbpSpeedTest.innerText = `${progressBarWidth}`;
            }
        }

        // Start speed test
        hbpStartRing.style.animation = 'none';

        hbpStartBorder.style.border = '12px transparent solid';
        hbpStartBorder.style.clip = 'rect(0px, 0px, 150px, 0px)';

        hbpSpeedTest.innerText = '00.00';
        hbpMbpsText.innerText = 'mbps';

        hbpRunning.innerHTML = "Running tests, please wait ...";

        const progressBarCount = setInterval(progressBar, 10);

        // Disable click
        click = true;
        this.style.cursor = 'not-allowed';
    }
}