 // TOP ACTIONS
 // VARIABLES
 const actionButtons = document.querySelector('.right-actions');
 const starForm = actionButtons ? actionButtons.querySelector('#star-form') : null;
 const starBtn = starForm ? starForm.querySelector('button') : null;
 const muteForm = actionButtons ? actionButtons.querySelector('#mute-form') : null;
 const muteBtn = muteForm ? muteForm.querySelector('button') : null;

 function addStarMuteEventListeners() {
     // Star Toggle
     if (starBtn) {
         let starImg = starBtn.querySelector('img');

         starBtn.addEventListener('click', async(e) => {
             e.preventDefault();
             e.stopPropagation();

             starBtn.disabled = true;
             starBtn.style.opacity = '0.5';
             starBtn.style.cursor = 'default';

             setTimeout(() => {
                 starBtn.disabled = false;
                 starBtn.style.opacity = '1';
                 starBtn.style.cursor = 'pointer';
             }, 400);

             try {
                 const response = await fetch(starForm.action, {
                     method: 'POST',
                     headers: {
                         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                         'Accept': 'application/json',
                         'Content-Type': 'application/x-www-form-urlencoded',
                     },
                     credentials: 'same-origin',
                     body: new URLSearchParams({
                         _token: document.querySelector('meta[name="csrf-token"]').content,
                     })
                 });

                 if (response.ok) {
                     const data = await response.json();

                     starImg.src = data.starValue ?
                         '/icons/star.png' :
                         '/icons/star-alt.png';
                 }
             } catch (error) {
                 console.error('Error: ', error);
             }
         })
     }
     // Mute Toggle
     if (muteBtn) {
         let muteImg = muteBtn.querySelector('img');

         muteBtn.addEventListener('click', async(e) => {
             e.preventDefault();
             e.stopPropagation();

             muteBtn.disabled = true;
             muteBtn.style.opacity = '0.5';
             muteBtn.style.cursor = 'default';

             setTimeout(() => {
                 muteBtn.disabled = false;
                 muteBtn.style.opacity = '1';
                 muteBtn.style.cursor = 'pointer';
             }, 400);

             try {
                 const response = await fetch(muteForm.action, {
                     method: 'POST',
                     headers: {
                         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                         'Accept': 'application/json',
                         'Content-Type': 'application/x-www-form-urlencoded',
                     },
                     credentials: 'same-origin',
                     body: new URLSearchParams({
                         _token: document.querySelector('meta[name="csrf-token"]').content,
                     })
                 });

                 if (response.ok) {
                     const data = await response.json();

                     muteImg.src = data.muteValue ?
                         '/icons/mute.png' :
                         '/icons/mute-alt.png';
                 }
             } catch (error) {
                 console.error('Error: ', error);
             }
         })
     }
 }

 function addJoinLeaveEventListeners() {
     // Join/Leave Toggle
     const JLForm = document.querySelector('#join-leave-form');
     if (!JLForm) return;

     // Remove previous event listeners by replacing the form node
     const newJLForm = JLForm.cloneNode(true);
     JLForm.parentNode.replaceChild(newJLForm, JLForm);

     // Reselect button from new form
     const JLBtn = newJLForm.querySelector('button');
     if (!JLBtn) return;

     // Join Group
     if (JLBtn.classList.contains('join-button')) {
         newJLForm.action = `/group/{{ $group->id }}/join`;
         newJLForm.addEventListener('submit', async(e) => {
             console.log(newJLForm.action);
             e.preventDefault();
             e.stopPropagation();
             try {
                 const response = await fetch(newJLForm.action, {
                     method: 'POST',
                     headers: {
                         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                         'Accept': 'application/json',
                         'Content-Type': 'application/x-www-form-urlencoded'
                     },
                     credentials: 'same-origin',
                     body: new URLSearchParams({
                         _token: document.querySelector('meta[name="csrf-token"]').content
                     })
                 });

                 if (response.ok) {
                     const data = await response.json();
                     if (data.membership === 1) {
                         newJLForm.innerHTML =
                             `<input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                        <button type="submit" class="leave-button">Leave</button>`;
                         addJoinLeaveEventListeners();

                         // Show star/mute forms
                         document.querySelector('#star-mute-forms').innerHTML = `
                                        <form action="/group/toggleStar/{{ $group->id }}" method="POST" id="star-form">
                                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                            <button class="star">
                                                <img src="/icons/star-alt.png" alt="star">
                                            </button>
                                        </form>
                                        <form action="/group/toggleMute/{{ $group->id }}" method="POST" id="mute-form">
                                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                            <button class="mute">
                                                <img src="/icons/mute-alt.png" alt="Mute">
                                            </button>
                                        </form>
                                    `;
                         addStarMuteEventListeners();
                     }
                 }
             } catch (error) {
                 console.error('Error: ', error);
             }
         });
     }

     // Leave Group
     if (JLBtn.classList.contains('leave-button')) {
         newJLForm.action = `/group/{{ $group->id }}/leave`;
         newJLForm.addEventListener('submit', async(e) => {
             console.log(newJLForm.action);
             e.preventDefault();
             e.stopPropagation();
             try {
                 const response = await fetch(newJLForm.action, {
                     method: 'POST',
                     headers: {
                         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                         'Accept': 'application/json',
                         'Content-Type': 'application/x-www-form-urlencoded'
                     },
                     credentials: 'same-origin',
                     body: new URLSearchParams({
                         _token: document.querySelector('meta[name="csrf-token"]').content
                     })
                 });

                 if (response.ok) {
                     const data = await response.json();
                     if (data.membership === 0) {
                         newJLForm.innerHTML =
                             `<input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                        <button type="submit" class="join-button">Join</button>`;
                         addJoinLeaveEventListeners();

                         document.querySelector('#star-mute-forms').innerHTML = ``;
                     }
                 }
             } catch (error) {
                 console.error('Error: ', error);
             }
         });
     }

     // Manage Group
     if (JLBtn.classList.contains('manage-button')) {
         newJLForm.addEventListener('submit', (e) => {
             e.preventDefault();
             e.stopPropagation();
             showGroupSettingsModal(); // Show modal instead of navigating
         })
     }
     // Request Button
     if (JLBtn.classList.contains('request-button')) {
         newJLForm.action = `/group/{{ $group->id }}/request`;
         newJLForm.addEventListener('submit', async(e) => {
             e.preventDefault();
             e.stopPropagation();
             try {
                 const response = await fetch(newJLForm.action, {
                     method: 'POST',
                     headers: {
                         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                         'Accept': 'application/json',
                         'Content-Type': 'application/x-www-form-urlencoded'
                     },
                     credentials: 'same-origin',
                     body: new URLSearchParams({
                         _token: document.querySelector('meta[name="csrf-token"]').content,
                     })
                 });

                 if (response.ok) {
                     const data = await response.json();
                     if (data.requested) {
                         newJLForm.innerHTML =
                             `<input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                        <button type="submit" class="request-button" disabled>Request to Join</button>`;
                         addJoinLeaveEventListeners();
                     }
                 }
             } catch (error) {
                 console.error("Error: ", error);
             }
         })
     }
 }
 // ACTIVATE!!!
 if (starForm && muteForm) addStarMuteEventListeners();
 addJoinLeaveEventListeners();
 // RIGHT ACTIONS
 // LEFT ACTIONS
 // MAIN CONTENT ACTIONS
 // POST
 // Variables
 const posts = document.querySelectorAll('.post');

 function addPostEventListeners() {
     // Link post cards to post pages
     Array.from(posts).forEach(post => {
         post.addEventListener('click', () => {
             window.location.href = `/post/${post.id}`;
         });
         // Post share  buttons
         const shareBtn = post.querySelector('.post-share-button');
         shareBtn.addEventListener('click', (e) => {
                 e.stopPropagation();
                 postUrl = `${window.location.origin}/post/${post.id}`;
                 navigator.clipboard.writeText(postUrl)
                     .then(() => {
                         shareBtn.textContent = 'Copied!';
                         setTimeout(() => {
                             shareBtn.textContent = 'Share';
                         }, 1200);
                     })
             })
             // Upvote & Downvote Function)
         const voteContainer = post.querySelector('#vote-container');
         const upvoteForm = voteContainer.querySelector('form:first-child');
         const downvoteForm = voteContainer.querySelector('form:last-child');
         const voteCount = voteContainer.querySelector('form:first-child + p');

         voteContainer.addEventListener('click', (e) => {
             e.stopPropagation();
         });
         // Upvote Logic
         upvoteForm.addEventListener('submit', async(e) => {
                 e.preventDefault();
                 e.stopPropagation();

                 try {
                     const response = await fetch(upvoteForm.action, {
                         method: 'POST',
                         headers: {
                             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                             'Accept': 'application/json',
                             'Content-Type': 'application/x-www-form-urlencoded'
                         },
                         credentials: 'same-origin',
                         body: new URLSearchParams({
                             _token: document.querySelector('meta[name="csrf-token"]').content
                         })
                     });

                     if (response.ok) {
                         const data = await response.json();

                         voteCount.textContent = data.voteCount;

                         const upArrow = upvoteForm.querySelector('img');
                         upArrow.src = data.voteValue == 1 ?
                             "/icons/up-arrow-alt.png" :
                             "/icons/up-arrow.png";

                         const downArrow = downvoteForm.querySelector('img');
                         if (data.voteValue == 1) {
                             downArrow.src = "/icons/down-arrow.png";
                         }
                     }
                 } catch (error) {
                     console.error("Error: ", error);
                 }
             })
             // Downvote Logic
         downvoteForm.addEventListener('submit', async(e) => {
             e.preventDefault();
             e.stopPropagation();

             try {
                 const response = await fetch(downvoteForm.action, {
                     method: 'POST',
                     headers: {
                         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                         'Accept': 'application/json',
                         'Content-Type': 'application/x-www-form-urlencoded'
                     },
                     credentials: 'same-origin',
                     body: new URLSearchParams({
                         _token: document.querySelector('meta[name="csrf-token"]').content
                     })
                 });

                 if (response.ok) {
                     const data = await response.json();

                     voteCount.textContent = data.voteCount;

                     const downArrow = downvoteForm.querySelector('img');
                     downArrow.src = data.voteValue == -1 ?
                         "/icons/down-arrow-alt.png" :
                         "/icons/down-arrow.png";

                     const upArrow = upvoteForm.querySelector('img');
                     if (data.voteValue == -1) {
                         upArrow.src = "/icons/up-arrow.png";
                     }
                 }
             } catch (error) {
                 console.error('Error:', error);
             }
         })
     })
 }

 // ACTIVATE!!!
 addPostEventListeners();
 //