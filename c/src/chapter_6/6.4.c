#include <stdio.h>

int main(void)
{
	char str[][60] = {
		"To be or not to be",
		", that is the question"	
	};
	int count[] = {0, 0};
	
	int i = 0;
	for(i = 0; i < 2; i++) {
		while (str[i][count[i]]) {
			count[i]++;	
		}
	}

	if (sizeof(str[0]) < count[0] + count[1] + 1) {
		printf("\nYou can't put a quart into a pint pot.");	
	} else {
		count[1] = 0;
		while((str[0][count[0]++] = str[1][count[1]++]));
		printf("\n%s\n", str[0]);	
	}
}
