

const VModal = window["vue-js-modal"].default
Vue.use(VModal);

var res=[];

let calendar = new Vue( {

    data:{
        
        weeks : ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        date : new Date(),
        year : null,
        month: null,
        startDay : null,
        startDate :null,
        endDate : null,
        endDayCount :null,
        enmptyCount : 0,
        info : null,
        selectDay:null,
        selecMonth:null,
        selectYear:null,
        text:"",
        schText:[],
        isSelect:false
      },
    created: function () {
        this.GetTextData();
        this.year=this.date.getFullYear();
        this.month =this.date.getMonth() + 1;
        this.selectDay=this.date.getDate();
        this.selecMonth=this.month;
        this.selectYear=this.year;
        this.CreateCalendar();
        console.log(this.info);        
        
    },

    template:
    '<div>'+
        '<div class="calendar">'+
            '<h1 class="u-ta-c">{{year}}/{{month}}</h1>'+
            '<div class="u-ta-c u-mb-20"><span class="arrow" @click="ChengeCalendar(-1)"><</span>&nbsp<span class="arrow" @click="ChengeCalendar(1)">></span></div>'+
            '<div>'+
                '<span class="u-ta-c week" v-for="week in weeks">{{week}}</span>'+
            '</div>'+
            '<div>'+
                '<div class="item empty" v-for="n of enmptyCount"></div>'+
                '<div class="item num" v-for="n of endDayCount"  :key="n" v-on:click="CheckSchedule(n)">{{n}}</div>'+
            '</div>'+
            '<h2>SCHEDULE</h2>'+
            '<div v-if="isSelect">'+
            '<h3>{{selectYear}}/{{selecMonth}}/{{selectDay}}</h3>'+
                '<div v-if="schText.length > 0">'+
                    '<div class="sch" v-for="n of schText.length" :key="n">'+
                    '<span>schedule {{n}}</span>'+
                    '<span>{{schText[n-1]}}</span>'+
                    '<span><button v-on:click="DeleteSchedule(n-1)">Delete</button></span>'+
                    '</div>'+
                   
                '</div>'+
                '<div class="u-mt-20">'+
                    '<input v-model="text" placeholder="edit schedule">'+
                    '<button v-on:click="addSchedule">Add</button>'+
                '</div>'+
            '</div>'+
            '<div v-else>Please Click a date</div>'+
        '</div>'+
    '</div>',

    methods:{
        CreateCalendar:function(){

            this.startDate = new Date(this.year, this.month - 1, 1);
            this.startDay=this.startDate.getDay();
            this.endDate = new Date(this.year, this.month,  0); 
            this.endDayCount = this.endDate.getDate();
            console.log(this.weeks[this.startDate.getDay()]);
            let num=0;
            while(this.weeks[num]!=this.weeks[this.startDate.getDay()]){
                this.enmptyCount++;
                num++;
            }
            this.GetTextData();
        },

        ChengeCalendar:function(num){
            this.enmptyCount=0;
            let dummy=this.month+=(num);
            if(dummy==0){
                this.month=12;
                this.year-=1;
            }else if(dummy==13){
                this.month=1;
                this.year+=1;
            }else{
                this.month=dummy;
            }
            this.CreateCalendar();
        },

        GetTextData:function(){
            axios.get('http://ko00.php.xdomain.jp/calendar/load.php')
            .then(function (response) {
                // handle success
              console.log(response.data["textData"]);
              res=response.data;
            })
            .catch(function (error) {
                // handle error
              console.log(error);
            })
            .finally(function () {
                // always executed
            });
        },

        CheckSchedule: function(n){
            if(!this.isSelect)this.isSelect=true;
            let self=this;
            this.GetTextData();
            console.log(n);
            this.selectDay=n;
            this.selecMonth=this.month;
            this.selectYear=this.year;
            var selectStr=String(this.selectYear)+"/"+String(this.selecMonth)+"/"+String(this.selectDay);
            
            for(var i = 0; i < res["textData"].length; i++){
                self.schText=[];
                if (res["textData"][i]["ymd"] == selectStr) {
                    self.schText=res["textData"][i]["text"];
                   
                    break;
                }
            }
            // console.log("hoge");
            // console.log(this.schText);
            
        },


        DeleteSchedule : function(n) {
            
            console.log("n="+n);
            // console.log("hoge"+this.schText.splice(1,1));
            if(Array.isArray(this.schText)){
                this.schText.splice(n,1);   

                console.log(res);
                var selectStr=String(this.selectYear)+"/"+String(this.selecMonth)+"/"+String(this.selectDay);
                let self=this;
                for(var i = 0; i < res["textData"].length; i++){
                    if (res["textData"][i]["ymd"] == selectStr) {
                        res["textData"][i]["text"]=self.schText;
                        break;
                    }
                }
                this.PostData();

            }
           
               
        },

        addSchedule : function() {
            let self=this;
            if(this.text=="")return;
            this.schText.push(this.text);
            
            var selectStr=String(this.selectYear)+"/"+String(this.selecMonth)+"/"+String(this.selectDay);

            for(var i = 0; i < res["textData"].length; i++){
                if (res["textData"][i]["ymd"] == selectStr) {
                    res["textData"][i]["text"]=self.schText;
                    break;
                }else if(i==res["textData"].length-1){
                    let newData={
                        "ymd":selectStr,
                        "text":self.schText
                    };
                    res["textData"].push(newData);
                }
            }
            this.PostData();

            this.text="";
            
        },
        
        PostData:function(){


            axios.post('http://ko00.php.xdomain.jp/calendar/post.php', res)
            .then(function (response) {
                console.log(response.data)
            })
            .catch(function (error) {
                console.log(error)
            })

        }

    }
  
  } );
  
  // 要素にマウントする
  calendar.$mount( '#calendar' );
